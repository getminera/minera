#ifndef BLKMK_PRIVATE_H
#define BLKMK_PRIVATE_H

#include <stdbool.h>
#include <string.h>

#include <blktemplate.h>

// blkmaker.c
extern bool _blkmk_dblsha256(void *hash, const void *data, size_t datasz);
extern bool blkmk_sample_data_(blktemplate_t *, uint8_t *, unsigned int dataid);
extern char *blkmk_assemble_submission_(blktemplate_t *, const unsigned char *data, unsigned int dataid, blknonce_t nonce, bool foreign);

// blktemplate.c
extern void _blktxn_free(struct blktxn_t *);

// hex.c
extern void _blkmk_bin2hex(char *out, const void *data, size_t datasz);
extern bool _blkmk_hex2bin(void *o, const char *x, size_t len);

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
