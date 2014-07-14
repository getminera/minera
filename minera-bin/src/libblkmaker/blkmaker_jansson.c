/*
 * Copyright 2012-2014 Luke Dashjr
 *
 * This program is free software; you can redistribute it and/or modify it
 * under the terms of the standard MIT license.  See COPYING for more details.
 */

#define _BSD_SOURCE

#include <stdlib.h>
#include <string.h>

#ifndef WIN32
#include <arpa/inet.h>
#else
#include <winsock2.h>
#endif

#include <jansson.h>

#include <blkmaker.h>
#include <blktemplate.h>

#include "private.h"

#ifndef JSON_INTEGER_IS_LONG_LONG
#	error "Jansson 2.0 with long long support required!"
#endif

json_t *blktmpl_request_jansson(gbt_capabilities_t caps, const char *lpid) {
	json_t *req, *jcaps, *jstr, *reqf, *reqa;
	if (!(req = json_object()))
		return NULL;
	jstr = reqa = jcaps = NULL;
	if (!(reqf = json_object()))
		goto err;
	if (!(reqa = json_array()))
		goto err;
	if (!(jcaps = json_array()))
		goto err;
	for (int i = 0; i < GBT_CAPABILITY_COUNT; ++i)
		if (caps & (1 << i))
		{
			jstr = json_string(blktmpl_capabilityname(1 << i));
			if (!jstr)
				goto err;
			if (json_array_append_new(jcaps, jstr))
				goto err;
		}
	jstr = NULL;
	if (json_object_set_new(req, "capabilities", jcaps))
		goto err;
	jcaps = NULL;
	if (!(jstr = json_integer(0)))
		goto err;
	if (json_object_set_new(reqf, "id", jstr))
		goto err;
	if (!(jstr = json_integer(BLKMAKER_MAX_BLOCK_VERSION)))
		goto err;
	if (json_object_set_new(req, "maxversion", jstr))
		goto err;
	if (lpid)
	{
		if (!(jstr = json_string(lpid)))
			goto err;
		if (json_object_set_new(req, "longpollid", jstr))
			goto err;
	}
	if (!(jstr = json_string("getblocktemplate")))
		goto err;
	if (json_object_set_new(reqf, "method", jstr))
		goto err;
	jstr = NULL;
	if (json_array_append_new(reqa, req))
		goto err;
	req = NULL;
	if (json_object_set_new(reqf, "params", reqa))
		goto err;
	
	return reqf;

err:
	if (req  )  json_decref(req  );
	if (reqa )  json_decref(reqa );
	if (reqf )  json_decref(reqf );
	if (jcaps)  json_decref(jcaps);
	if (jstr )  json_decref(jstr );
	return NULL;
}


#define my_hex2bin _blkmk_hex2bin

#define GET(key, type)  do {  \
	if (!(v = json_object_get(json, #key)))  \
		return "Missing '" #key "'";         \
	if (!json_is_ ## type(v))                \
		return "Wrong type for '" #key "'";  \
} while(0)

#define GETHEX(key, skey)  do {  \
	GET(key, string);                                                       \
	if (!my_hex2bin(tmpl->skey, json_string_value(v), sizeof(tmpl->skey)))  \
		return "Error decoding '" #key "'";                                 \
} while(0)

#define GETNUM(key)  do {  \
	GET(key, number);                       \
	tmpl->key = json_integer_value(v);      \
} while(0)

#define GETNUM_O2(key, skey)  do {  \
	if ((v = json_object_get(json, #skey)) && json_is_number(v))  \
		tmpl->key = json_integer_value(v);  \
} while(0)

#define GETNUM_O(key)  GETNUM_O2(key, key)

#define GETSTR(key, skey)  do {  \
	if ((v = json_object_get(json, #key)) && json_is_string(v))  \
		if (!(tmpl->skey = strdup(json_string_value(v))))  \
			return "Error copying '" #key "'";  \
} while(0)

#define GETBOOL(key, skey, def)  do {  \
	if ((v = json_object_get(json, #key)) && json_is_boolean(v))  \
		tmpl->skey = json_is_true(v);  \
	else  \
	if (def)  \
		tmpl->skey = true;  \
} while(0)

static void my_flip(void *, size_t);

static
const char *parse_txn(struct blktxn_t *txn, json_t *txnj) {
	json_t *vv;
	
	if (!((vv = json_object_get(txnj, "data")) && json_is_string(vv)))
		return "Missing or invalid type for transaction data";
	const char *hexdata = json_string_value(vv);
	size_t datasz = strlen(hexdata) / 2;
	txn->data = malloc(datasz);
	txn->datasz = datasz;
	if (!my_hex2bin(txn->data, hexdata, datasz))
		return "Error decoding transaction data";
	
	if ((vv = json_object_get(txnj, "hash")) && json_is_string(vv))
	{
		hexdata = json_string_value(vv);
		txn->hash_ = malloc(sizeof(*txn->hash_));
		if (!my_hex2bin(*txn->hash_, hexdata, sizeof(*txn->hash_)))
		{
			free(txn->hash_);
			txn->hash_ = NULL;
		}
		else
			my_flip(*txn->hash_, sizeof(*txn->hash_));
	}
	
	// TODO: dependcount/depends, fee, required, sigops
	
	return NULL;
}

static
void my_flip(void *data, size_t datasz) {
	char *cdata = (char*)data;
	--datasz;
	size_t hds = datasz / 2;
	for (size_t i = 0; i <= hds; ++i)
	{
		int altp = datasz - i;
		char c = cdata[i];
		cdata[i] = cdata[altp];
		cdata[altp] = c;
	}
}

const char *blktmpl_add_jansson(blktemplate_t *tmpl, const json_t *json, time_t time_rcvd) {
	if (tmpl->version)
		return "Template already populated (combining not supported)";
	
	json_t *v, *v2;
	const char *s;
	
	if ((v = json_object_get(json, "result")))
	{
		json_t *je;
		if ((je = json_object_get(json, "error")) && !json_is_null(je))
			return "JSON result is error";
		json = v;
	}
	
	GETHEX(bits, diffbits);
	my_flip(tmpl->diffbits, 4);
	GETNUM(curtime);
	GETNUM(height);
	GETHEX(previousblockhash, prevblk);
	my_flip(tmpl->prevblk, 32);
	GETNUM_O(sigoplimit);
	GETNUM_O(sizelimit);
	GETNUM(version);
	
	GETNUM_O2(cbvalue, coinbasevalue);
	
	GETSTR(workid, workid);
	
	GETNUM_O(expires);
	
	GETSTR(longpollid, lp.id);
	GETSTR(longpolluri, lp.uri);
	GETBOOL(submitold, submitold, true);
	
	v = json_object_get(json, "transactions");
	size_t txns = tmpl->txncount = json_array_size(v);
	tmpl->txns = calloc(txns, sizeof(*tmpl->txns));
	for (size_t i = 0; i < txns; ++i)
		if ((s = parse_txn(&tmpl->txns[i], json_array_get(v, i))))
			return s;
	
	if ((v = json_object_get(json, "coinbasetxn")) && json_is_object(v))
	{
		tmpl->cbtxn = calloc(1, sizeof(*tmpl->cbtxn));
		if ((s = parse_txn(tmpl->cbtxn, v)))
			return s;
	}
	
	// TODO: coinbaseaux
	
	if ((v = json_object_get(json, "mutable")) && json_is_array(v))
	{
		for (size_t i = json_array_size(v); i--; )
		{
			v2 = json_array_get(v, i);
			if (!json_is_string(v2))
				continue;
			tmpl->mutations |= blktmpl_getcapability(json_string_value(v2));
		}
	}
	
	if (tmpl->version > 2 || (tmpl->version == 2 && !tmpl->height))
	{
		if (tmpl->mutations & BMM_VERDROP)
			tmpl->version = tmpl->height ? 2 : 1;
		else
		if (!(tmpl->mutations & BMM_VERFORCE))
			return "Unrecognized block version, and not allowed to reduce or force it";
	}
	
	tmpl->_time_rcvd = time_rcvd;
	
	return NULL;
}

static
char varintEncode(unsigned char *out, uint64_t n) {
	if (n < 0xfd)
	{
		out[0] = n;
		return 1;
	}
	char L;
	if (n <= 0xffff)
	{
		out[0] = '\xfd';
		L = 3;
	}
	else
	if (n <= 0xffffffff)
	{
		out[0] = '\xfe';
		L = 5;
	}
	else
	{
		out[0] = '\xff';
		L = 9;
	}
	for (unsigned char i = 1; i < L; ++i)
		out[i] = (n >> ((i - 1) * 8)) % 256;
	return L;
}

#define my_bin2hex _blkmk_bin2hex

static
json_t *_blkmk_submit_jansson(blktemplate_t *tmpl, const unsigned char *data, unsigned int dataid, blknonce_t nonce, bool foreign) {
	unsigned char blk[80 + 8 + 1000000];
	memcpy(blk, data, 76);
	nonce = htonl(nonce);
	memcpy(&blk[76], &nonce, 4);
	size_t offs = 80;
	
	if (foreign || (!(tmpl->mutations & BMAb_TRUNCATE && !dataid)))
	{
		offs += varintEncode(&blk[offs], 1 + tmpl->txncount);
		
		if (!_blkmk_extranonce(tmpl, &blk[offs], dataid, &offs))
			return NULL;
		
		if (foreign || !(tmpl->mutations & BMAb_COINBASE))
			for (unsigned long i = 0; i < tmpl->txncount; ++i)
			{
				memcpy(&blk[offs], tmpl->txns[i].data, tmpl->txns[i].datasz);
				offs += tmpl->txns[i].datasz;
			}
	}
	
	char blkhex[(offs * 2) + 1];
	my_bin2hex(blkhex, blk, offs);
	
	json_t *rv = json_array(), *ja, *jb;
	jb = NULL;
	if (!(ja = json_string(blkhex)))
		goto err;
	if (json_array_append_new(rv, ja))
		goto err;
	if (!(ja = json_object()))
		goto err;
	if (tmpl->workid && !foreign)
	{
		if (!(jb = json_string(tmpl->workid)))
			goto err;
		if (json_object_set_new(ja, "workid", jb))
			goto err;
		jb = NULL;
	}
	if (json_array_append_new(rv, ja))
		goto err;
	
	if (!(ja = json_object()))
		goto err;
	if (!(jb = json_integer(0)))
		goto err;
	if (json_object_set_new(ja, "id", jb))
		goto err;
	if (!(jb = json_string("submitblock")))
		goto err;
	if (json_object_set_new(ja, "method", jb))
		goto err;
	jb = NULL;
	if (json_object_set_new(ja, "params", rv))
		goto err;
	
	return ja;

err:
	json_decref(rv);
	if (ja)  json_decref(ja);
	if (jb)  json_decref(jb);
	return NULL;
}

json_t *blkmk_submit_jansson(blktemplate_t *tmpl, const unsigned char *data, unsigned int dataid, blknonce_t nonce) {
	return _blkmk_submit_jansson(tmpl, data, dataid, nonce, false);
}

json_t *blkmk_submit_foreign_jansson(blktemplate_t *tmpl, const unsigned char *data, unsigned int dataid, blknonce_t nonce) {
	return _blkmk_submit_jansson(tmpl, data, dataid, nonce, true);
}
