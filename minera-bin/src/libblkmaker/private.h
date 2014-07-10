#ifndef BLKMK_PRIVATE_H
#define BLKMK_PRIVATE_H

#include <stdbool.h>
#include <string.h>

// blkmaker.c
extern bool _blkmk_dblsha256(void *hash, const void *data, size_t datasz);

// blktemplate.c
extern void _blktxn_free(struct blktxn_t *);

// hex.c
extern void _blkmk_bin2hex(char *out, const void *data, size_t datasz);
extern bool _blkmk_hex2bin(void *o, const char *x, size_t len);

// base58.c
extern bool _blkmk_b58tobin(void *bin, size_t binsz, const char *b58, size_t b58sz);
extern int _blkmk_b58check(void *bin, size_t binsz, const char *b58);

// inline

// NOTE: This must return 0 for 0
static inline
int blkmk_flsl(unsigned long n)
{
	int i;
	for (i = 0; n; ++i)
		n >>= 1;
	return i;
}

#endif
