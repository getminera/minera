<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * CodeIgniter Redis
 *
 * A CodeIgniter library to interact with Redis
 *
 * @package        	CodeIgniter
 * @category    	Libraries
 * @author        	Jo‘l Cox
 * @version			v0.4
 * @link 			https://github.com/joelcox/codeigniter-redis
 * @link			http://joelcox.nl
 * @license			http://www.opensource.org/licenses/mit-license.html
 */
class CI_Redis {

	/**
	 * CI
	 *
	 * CodeIgniter instance
	 * @var 	object
	 */
	private $_ci;

	/**
	 * Connection
	 *
	 * Socket handle to the Redis server
	 * @var		handle
	 */
	private $_connection;

	/**
	 * Debug
	 *
	 * Whether we're in debug mode
	 * @var		bool
	 */
	public $debug = FALSE;

	/**
	 * CRLF
	 *
	 * User to delimiter arguments in the Redis unified request protocol
	 * @var		string
	 */
	const CRLF = "\r\n";

	/**
	 * Constructor
	 */
	public function __construct($params = array())
	{

		log_message('debug', 'Redis Class Initialized');

		$this->_ci = get_instance();
		$this->_ci->load->config('redis');

		// Check for the different styles of configs
		if (isset($params['connection_group']))
		{
			// Specific connection group
			$config = $this->_ci->config->item('redis_' . $params['connection_group']);
		}
		elseif (is_array($this->_ci->config->item('redis_default')))
		{
			// Default connection group
			$config = $this->_ci->config->item('redis_default');
		}
		else
		{
			// Original config style
			$config = array(
				'host' => $this->_ci->config->item('redis_host'),
				'port' => $this->_ci->config->item('redis_port'),
				'password' => $this->_ci->config->item('redis_password'),
			);
		}

		// Connect to Redis
		$this->_connection = @fsockopen($config['host'], $config['port'], $errno, $errstr, 3);

		// Display an error message if connection failed
		if ( ! $this->_connection)
		{
			show_error('Could not connect to Redis at ' . $config['host'] . ':' . $config['port']);
		}

		// Authenticate when needed
		$this->_auth($config['password']);

	}

	/**
	 * Call
	 *
	 * Catches all undefined methods
	 * @param	string	method that was called
	 * @param	mixed	arguments that were passed
	 * @return 	mixed
	 */
	public function __call($method, $arguments)
	{
		$request = $this->_encode_request($method, $arguments);
		return $this->_write_request($request);
	}

	/**
	 * Command
	 *
	 * Generic command function, just like redis-cli
	 * @param	string	full command as a string
	 * @return 	mixed
	 */
	public function command($string)
	{
		$slices = explode(' ', $string);
		$request = $this->_encode_request($slices[0], array_slice($slices, 1));

		return $this->_write_request($request);
	}

	/**
	 * Auth
	 *
	 * Runs the AUTH command when password is set
	 * @param 	string	password for the Redis server
	 * @return 	void
	 */
	private function _auth($password = NULL)
	{

		// Authenticate when password is set
		if ( ! empty($password))
		{

			// See if we authenticated successfully
			if ($this->command('AUTH ' . $password) !== 'OK')
			{
				show_error('Could not connect to Redis, invalid password');
			}

		}

	}

	/**
	 * Clear Socket
	 *
	 * Empty the socket buffer of theconnection so data does not bleed over
	 * to the next message.
	 * @return 	NULL
	 */
	public function _clear_socket()
	{
		// Read one character at a time
		fflush($this->_connection);
		return NULL;
	}

	/**
	 * Write request
	 *
	 * Write the formatted request to the socket
	 * @param	string 	request to be written
	 * @return 	mixed
	 */
	private function _write_request($request)
	{
		if ($this->debug === TRUE)
		{
			log_message('debug', 'Redis unified request: ' . $request);
		}

		// How long is the data we are sending?
		$value_length = strlen($request);

		// If there isn't any data, just return
		if ($value_length <= 0) return NULL;


		// Handle reply if data is less than or equal to 8192 bytes, just send it over
		if ($value_length <= 8192)
		{
			fwrite($this->_connection, $request);
		}
		else
		{
			while ($value_length > 0)
			{

				// If we have more than 8192, only take what we can handle
				if ($value_length > 8192) {
					$send_size = 8192;
				}

				// Send our chunk
				fwrite($this->_connection, $request, $send_size);

				// How much is left to send?
				$value_length = $value_length - $send_size;

				// Remove data sent from outgoing data
				$request = substr($request, $send_size, $value_length);

			}
		}

		// Read our request into a variable
		$return = $this->_read_request();

		// Clear the socket so no data remains in the buffer
		$this->_clear_socket();

		return $return;
	}

	/**
	 * Read request
	 *
	 * Route each response to the appropriate interpreter
	 * @return 	mixed
	 */
	private function _read_request()
	{
		$type = fgetc($this->_connection);

		// Times we will attempt to trash bad data in search of a
		// valid type indicator
		$response_types = array('+', '-', ':', '$', '*');
		$type_error_limit = 50;
		$try = 0;

		while ( ! in_array($type, $response_types) && $try < $type_error_limit)
		{
			$type = fgetc($this->_connection);
			$try++;
		}

		if ($this->debug === TRUE)
		{
			log_message('debug', 'Redis response type: ' . $type);
		}

		switch ($type)
		{
			case '+':
				return $this->_single_line_reply();
				break;
			case '-':
				return $this->_error_reply();
				break;
			case ':':
				return $this->_integer_reply();
				break;
			case '$':
				return $this->_bulk_reply();
				break;
			case '*':
				return $this->_multi_bulk_reply();
				break;
			default:
				return FALSE;
		}

	}

	/**
	 * Single line reply
	 *
	 * Reads the reply before the EOF
	 * @return 	mixed
	 */
	private function _single_line_reply()
	{
		$value = rtrim(fgets($this->_connection));
        $this->_clear_socket();

		return $value;
	}

	/**
	 * Error reply
	 *
	 * Write error to log and return false
	 * @return 	bool
	 */
	private function _error_reply()
	{
		// Extract the error message
		$error = substr(rtrim(fgets($this->_connection)), 4);

		log_message('error', 'Redis server returned an error: ' . $error);
        $this->_clear_socket();

		return FALSE;
	}

	/**
	 * Integer reply
	 *
	 * Returns an integer reply
	 * @return 	int
	 */
	private function _integer_reply()
	{
		return (int) rtrim(fgets($this->_connection));
	}

    /**
     * Bulk reply
     *
     * Reads to amount of bits to be read and returns value within
     * the pointer and the ending delimiter
     * @return  string
     */
    private function _bulk_reply()
    {

		// How long is the data we are reading? Support waiting for data to
		// fully return from redis and enter into socket.
        $value_length = (int) fgets($this->_connection);

        if ($value_length <= 0) return NULL;

        $response = '';

		// Handle reply if data is less than or equal to 8192 bytes, just read it
		if ($value_length <= 8192)
		{
			$response = fread($this->_connection, $value_length);
		}
		else
		{
			$data_left = $value_length;

				// If the data left is greater than 0, keep reading
	        	while ($data_left > 0 ) {

				// If we have more than 8192, only take what we can handle
				if ($data_left > 8192)
				{
					$read_size = 8192;
				}
				else
				{
					$read_size = $data_left;
				}

				// Read our chunk
				$chunk = fread($this->_connection, $read_size);

				// Support reading very long responses that don't come through
				// in one fread

				$chunk_length = strlen($chunk);
				while ($chunk_length < $read_size)
				{
					$keep_reading = $read_size - $chunk_length;
					$chunk .= fread($this->_connection, $keep_reading);
					$chunk_length = strlen($chunk);
				}

				$response .= $chunk;

				// Re-calculate how much data is left to read
				$data_left = $data_left - $read_size;

			}

		}

		// Clear the socket in case anything remains in there
		$this->_clear_socket();

	return isset($response) ? $response : FALSE;
    }

	/**
	 * Multi bulk reply
	 *
	 * Reads n bulk replies and return them as an array
	 * @return 	array
	 */
	private function _multi_bulk_reply()
	{
		// Get the amount of values in the response
		$response = array();
		$total_values = (int) fgets($this->_connection);

		// Loop all values and add them to the response array
		for ($i = 0; $i < $total_values; $i++)
		{
			// Remove the new line and carriage return before reading
			// another bulk reply
			fgets($this->_connection, 2);

			// If this is a second or later pass, we also need to get rid
			// of the $ indicating a new bulk reply and its length.
			if ($i > 0)
			{
				fgets($this->_connection);
				fgets($this->_connection, 2);
			}

			$response[] = $this->_bulk_reply();

		}

		// Clear the socket
		$this->_clear_socket();

		return isset($response) ? $response : FALSE;
	}

	/**
	 * Encode request
	 *
	 * Encode plain-text request to Redis protocol format
	 * @link 	http://redis.io/topics/protocol
	 * @param 	string 	request in plain-text
	 * @param   string  additional data (string or array, depending on the request)
	 * @return 	string 	encoded according to Redis protocol
	 */
    private function _encode_request($method, $arguments = array())
    {
        $request = '$' . strlen($method) . self::CRLF . $method . self::CRLF;
        $_args = 1;

        // Append all the arguments in the request string
        foreach ($arguments as $argument)
        {
            if (is_array($argument))
            {
                foreach ($argument as $key => $value)
                {
                    // Prepend the key if we're dealing with a hash
                    if (!is_int($key))
                    {
                        $request .= '$' . strlen($key) . self::CRLF . $key . self::CRLF;
                        $_args++;
                    }

                    $request .= '$' . strlen($value) . self::CRLF . $value . self::CRLF;
                    $_args++;
                }
            }
            else
            {
                $request .= '$' . strlen($argument) . self::CRLF . $argument . self::CRLF;
                $_args++;
            }
        }

        $request = '*' . $_args . self::CRLF . $request;

        return $request;
    }

	/**
	 * Info
	 *
	 * Overrides the default Redis response, so we can return a nice array
	 * of the server info instead of a nasty string.
	 * @return 	array
	 */
	public function info($section = FALSE)
	{
		if ($section !== FALSE)
		{
			$response = $this->command('INFO '. $section);
		}
		else
		{
			$response = $this->command('INFO');
		}

		$data = array();
		$lines = explode(self::CRLF, $response);

		// Extract the key and value
		foreach ($lines as $line)
		{
			$parts = explode(':', $line);
			if (isset($parts[1])) $data[$parts[0]] = $parts[1];
		}

		return $data;
	}

	/**
	 * Debug
	 *
	 * Set debug mode
	 * @param	bool 	set the debug mode on or off
	 * @return 	void
	 */
	public function debug($bool)
	{
		$this->debug = (bool) $bool;
	}

	/**
	 * Destructor
	 *
	 * Kill the connection
	 * @return 	void
	 */
	function __destruct()
	{
		if ($this->_connection) fclose($this->_connection);
	}
}