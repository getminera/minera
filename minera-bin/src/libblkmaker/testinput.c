/*
 * Written by Luke Dashjr in 2012
 *
 * This data is released under the terms of the Creative Commons "CC0 1.0 Universal" license and/or copyright waiver.
 */

const char *blkmaker_test_input =
"{"
	"\"result\": {"
		"\"previousblockhash\": \"000000004d424dec1c660a68456b8271d09628a80cc62583e5904f5894a2483c\","
		"\"target\": \"00000000ffffffffffffffffffffffffffffffffffffffffffffffffffffffff\","
		"\"noncerange\": \"00000000ffffffff\","
		"\"transactions\": [],"
		"\"sigoplimit\": 20000,"
		"\"expires\": 120,"
		"\"longpoll\": \"/LP\","
		"\"height\": 23957,"
		"\"coinbasetxn\": {"
			"\"data\": \""
				"01000000"  // txn version
				"01"        // txn in count
				"0000000000000000000000000000000000000000000000000000000000000000"  // input coin
				"ffffffff"
				"13" "02955d0f00456c6967697573005047dc66085f"  // scriptSig
				"ffffffff"                                     // sequence
				"02"         // tx out count
				"fff1052a01000000"                                         // tx out #1 amount
				"19" "76a9144ebeb1cd26d6227635828d60d3e0ed7d0da248fb88ac"  // tx out #1 scriptPubKey
				"0100000000000000"                                         // tx out #2 amount
				"19" "76a9147c866aee1fa2f3b3d5effad576df3dbf1f07475588ac"  // tx out #2 scriptPubKey
				"00000000"   // lock time
			"\""
		"},"
		"\"version\": 2,"
		"\"curtime\": 1346886758,"
		"\"mutable\": [\"coinbase/append\"],"
		"\"sizelimit\": 1000000,"
		"\"bits\": \"ffff001d\""
	"},"
	"\"id\": 0,"
	"\"error\": null"
"}"
;