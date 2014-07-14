/*
 * Copyright 2012 Luke Dashjr
 *
 * This program is free software; you can redistribute it and/or modify it
 * under the terms of the standard MIT license.  See COPYING for more details.
 */

#include <string.h>

#include <stdbool.h>

bool _blkmk_hex2bin(void *o, const char *x, size_t len) {
	unsigned char *oc = o;
	unsigned char c, hc = 0x10;
	len *= 2;
	while (len)
	{
		switch (x[0]) {
		case '0': case '1': case '2': case '3': case '4':
		case '5': case '6': case '7': case '8': case '9':
			c = x[0] - '0';
			break;
		case 'A': case 'B': case 'C': case 'D': case 'E': case 'F':
			c = x[0] - 'A' + 10;
			break;
		case 'a': case 'b': case 'c': case 'd': case 'e': case 'f':
			c = x[0] - 'a' + 10;
			break;
		default:
			return false;
		}
		++x;
		if (hc < 0x10)
		{
			(oc++)[0] = (hc << 4) | c;
			hc = 0x10;
		}
		else
			hc = c;
		--len;
	}
	return !x[0];
}

void _blkmk_bin2hex(char *out, const void *data, size_t datasz) {
	const unsigned char *datac = data;
	static char hex[] = "0123456789abcdef";
	out[datasz * 2] = '\0';
	for (size_t i = 0; i < datasz; ++i)
	{
		out[ i*2   ] = hex[datac[i] >> 4];
		out[(i*2)+1] = hex[datac[i] & 15];
	}
}
