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

#include <blkmaker.h>

#include "private.h"

static const int8_t b58digits[] = {
	-1,-1,-1,-1,-1,-1,-1,-1, -1,-1,-1,-1,-1,-1,-1,-1,
	-1,-1,-1,-1,-1,-1,-1,-1, -1,-1,-1,-1,-1,-1,-1,-1,
	-1,-1,-1,-1,-1,-1,-1,-1, -1,-1,-1,-1,-1,-1,-1,-1,
	-1, 0, 1, 2, 3, 4, 5, 6,  7, 8,-1,-1,-1,-1,-1,-1,
	-1, 9,10,11,12,13,14,15, 16,-1,17,18,19,20,21,-1,
	22,23,24,25,26,27,28,29, 30,31,32,-1,-1,-1,-1,-1,
	-1,33,34,35,36,37,38,39, 40,41,42,43,-1,44,45,46,
	47,48,49,50,51,52,53,54, 55,56,57,-1,-1,-1,-1,-1,
};

bool _blkmk_b58tobin(void *bin, size_t binsz, const char *b58, size_t b58sz) {
	const unsigned char *b58u = (void*)b58;
	unsigned char *binu = bin;
	size_t outisz = (binsz + 3) / 4;
	uint32_t outi[outisz];
	uint64_t t;
	uint32_t c;
	size_t i, j;
	uint8_t bytesleft = binsz % 4;
	uint32_t zeromask = ~((1 << ((bytesleft) * 8)) - 1);
	
	if (!b58sz)
		b58sz = strlen(b58);
	
	memset(outi, 0, outisz * sizeof(*outi));
	
	for (i = 0; i < b58sz; ++i)
	{
		if (b58u[i] & 0x80)
			// High-bit set on invalid digit
			return false;
		if (b58digits[b58u[i]] == -1)
			// Invalid base58 digit
			return false;
		c = b58digits[b58u[i]];
		for (j = outisz; j--; )
		{
			t = ((uint64_t)outi[j]) * 58 + c;
			c = (t & 0x3f00000000) >> 32;
			outi[j] = t & 0xffffffff;
		}
		if (c)
			// Output number too big (carry to the next int32)
			return false;
		if (outi[0] & zeromask)
			// Output number too big (last int32 filled too far)
			return false;
	}
	
	j = 0;
	switch (bytesleft) {
		case 3:
			*(binu++) = (outi[0] &   0xff0000) >> 16;
		case 2:
			*(binu++) = (outi[0] &     0xff00) >>  8;
		case 1:
			*(binu++) = (outi[0] &       0xff);
			++j;
		default:
			break;
	}
	
	for (; j < outisz; ++j)
	{
		*((uint32_t*)binu) = htonl(outi[j]);
		binu += sizeof(uint32_t);
	}
	return true;
}

int _blkmk_b58check(void *bin, size_t binsz, const char *base58str) {
	unsigned char buf[32];
	unsigned char *binc = bin;
	unsigned i;
	if (!_blkmk_dblsha256(buf, bin, binsz - 4))
		return -2;
	if (memcmp(&binc[binsz - 4], buf, 4))
		return -1;
	
	// Check number of zeros is correct AFTER verifying checksum (to avoid possibility of accessing base58str beyond the end)
	for (i = 0; binc[i] == '\0' && base58str[i] == '1'; ++i)
	{}  // Just finding the end of zeros, nothing to do in loop
	if (binc[i] == '\0' || base58str[i] == '1')
		return -3;
	
	return binc[0];
}

size_t blkmk_address_to_script(void *out, size_t outsz, const char *addr) {
	unsigned char addrbin[25];
	unsigned char *cout = out;
	int addrver;
	size_t rv;
	
	if (!_blkmk_b58tobin(addrbin, sizeof(addrbin), addr, 0))
		return 0;
	addrver = _blkmk_b58check(addrbin, sizeof(addrbin), addr);
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
