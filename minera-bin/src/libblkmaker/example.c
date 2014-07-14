/*
 * Copyright 2012 Luke Dashjr
 *
 * This program is free software; you can redistribute it and/or modify it
 * under the terms of the standard MIT license.  See COPYING for more details.
 */

#include <assert.h>
#include <inttypes.h>
#include <stdint.h>

#include <arpa/inet.h>

#include <gcrypt.h>

#include <blkmaker.h>
#include <blkmaker_jansson.h>

#include "private.h"
#include "testinput.c"

void testb58() {
	int rv;
	const char *iaddr = "11Baf75Ferj6A7AoN565gCQj9kGWbDMHfN9";
	const char *addr = &iaddr[1];
	char bufx[26] = {'\xff'};
	char *buf = &bufx[1];
	if (!_blkmk_b58tobin(buf, 25, addr, 0))
		exit(1);
	if (bufx[0] != '\xff')
		exit(2);
	char cbuf[51];
	_blkmk_bin2hex(cbuf, buf, 25);
	printf("Base58 raw data: %s\n", cbuf);
	assert((rv = _blkmk_b58check(buf, 25, addr)) == 0);
	printf("Base58 check: %d\n", rv);
	assert((rv = _blkmk_b58check(buf, 25, &addr[1])) < 0);
	printf("Base58 check (invalid/    unpadded): %d\n", rv);
	assert((rv = _blkmk_b58check(buf, 25, iaddr)) < 0);
	printf("Base58 check (invalid/extra padded): %d\n", rv);
}

static
void send_json(json_t *req) {
	char *s = json_dumps(req, JSON_INDENT(2));
	puts(s);
	free(s);
}

static
bool my_sha256(void *digest, const void *buffer, size_t length) {
	gcry_md_hash_buffer(GCRY_MD_SHA256, digest, buffer, length);
	return true;
}

int main(int argc, char**argv) {
	blktemplate_t *tmpl;
	json_t *req;
	json_error_t jsone;
	const char *err;
	
	blkmk_sha256_impl = my_sha256;
	
	testb58();
	
	tmpl = blktmpl_create();
	assert(tmpl);
	req = blktmpl_request_jansson(blktmpl_addcaps(tmpl), NULL);
	assert(req);
	
	// send req to server and parse response into req
	send_json(req);
	json_decref(req);
	if (argc == 2)
		req = json_loadf(stdin, JSON_DISABLE_EOF_CHECK, &jsone);
	else
	{
		req = json_loads(blkmaker_test_input, 0, &jsone);
		send_json(req);
	}
	assert(req);
	
	err = blktmpl_add_jansson(tmpl, req, time(NULL));
	json_decref(req);
	if (err)
	{
		fprintf(stderr, "Error adding block template: %s", err);
		assert(0 && "Error adding block template");
	}
	while (blkmk_time_left(tmpl, time(NULL)) && blkmk_work_left(tmpl))
	{
		unsigned char data[80], hash[32];
		size_t datasz;
		unsigned int dataid;
		uint32_t nonce;
		
		datasz = blkmk_get_data(tmpl, data, sizeof(data), time(NULL), NULL, &dataid);
		assert(datasz >= 76 && datasz <= sizeof(data));
		
		// mine the right nonce
		// this is iterating in native order, even though SHA256 is big endian, because we don't implement noncerange
		// however, the nonce is always interpreted as big endian, so we need to convert it as if it were big endian
		for (nonce = 0; nonce < 0xffffffff; ++nonce)
		{
			*(uint32_t*)(&data[76]) = nonce;
			assert(my_sha256(hash, data, 80));
			assert(my_sha256(hash, hash, 32));
			if (!*(uint32_t*)(&hash[28]))
				break;
			if (!(nonce % 0x1000))
			{
				printf("0x%8" PRIx32 " hashes done...\r", nonce);
				fflush(stdout);
			}
		}
		printf("Found nonce: 0x%8" PRIx32 " \n", nonce);
		nonce = ntohl(nonce);
		
		req = blkmk_submit_jansson(tmpl, data, dataid, nonce);
		assert(req);
		// send req to server
		send_json(req);
	}
	blktmpl_free(tmpl);
}
