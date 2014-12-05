/*
 * Copyright 2012 Luke Dashjr
 *
 * This program is free software; you can redistribute it and/or modify it
 * under the terms of the standard MIT license.  See COPYING for more details.
 */

#ifndef WIN32
#include <arpa/inet.h>
#else
#include <winsock2.h>
#endif

#include <stdbool.h>
#include <stdint.h>
#include <string.h>

#include <libbase58.h>

#include <blkmaker.h>

#include "private.h"

bool _blkmk_b58tobin(void *bin, size_t binsz, const char *b58, size_t b58sz) {
	return b58tobin(bin, &binsz, b58, b58sz);
}

int _blkmk_b58check(void *bin, size_t binsz, const char *base58str) {
	if (!b58_sha256_impl)
		b58_sha256_impl = blkmk_sha256_impl;
	return b58check(bin, binsz, base58str, 34);
}

size_t blkmk_address_to_script(void *out, size_t outsz, const char *addr) {
	unsigned char addrbin[25];
	unsigned char *cout = out;
	const size_t b58sz = strlen(addr);
	int addrver;
	size_t rv;
	
	rv = sizeof(addrbin);
	if (!b58_sha256_impl)
		b58_sha256_impl = blkmk_sha256_impl;
	if (!b58tobin(addrbin, &rv, addr, b58sz))
		return 0;
	addrver = b58check(addrbin, sizeof(addrbin), addr, b58sz);
	switch (addrver) {
		case   0:  // Bitcoin pubkey hash
		case 111:  // Testnet pubkey hash
			if (outsz < (rv = 25))
				return rv;
			cout[ 0] = 0x76;  // OP_DUP
			cout[ 1] = 0xa9;  // OP_HASH160
			cout[ 2] = 0x14;  // push 20 bytes
			memcpy(&cout[3], &addrbin[1], 20);
			cout[23] = 0x88;  // OP_EQUALVERIFY
			cout[24] = 0xac;  // OP_CHECKSIG
			return rv;
		case   5:  // Bitcoin script hash
		case 196:  // Testnet script hash
			if (outsz < (rv = 23))
				return rv;
			cout[ 0] = 0xa9;  // OP_HASH160
			cout[ 1] = 0x14;  // push 20 bytes
			memcpy(&cout[2], &addrbin[1], 20);
			cout[22] = 0x87;  // OP_EQUAL
			return rv;
		default:
			return 0;
	}
}
