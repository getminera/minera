/*
 * Copyright 2012-2013 Luke Dashjr
 *
 * This program is free software; you can redistribute it and/or modify it
 * under the terms of the standard MIT license.  See COPYING for more details.
 */

#define _BSD_SOURCE

#include <limits.h>
#include <stdbool.h>
#include <stdlib.h>
#include <string.h>

#include <blktemplate.h>

static const char *capnames[] = {
	"coinbasetxn",
	"coinbasevalue",
	"workid",
	
	"longpoll",
	"proposal",
	"serverlist",
	                                     NULL, NULL,
	NULL, NULL, NULL, NULL,  NULL, NULL, NULL, NULL,
	
	"coinbase/append",
	"coinbase",
	"generate",
	"time/increment",
	"time/decrement",
	"transactions/add",
	"prevblock",
	"version/force",
	"version/reduce",
	
	"submit/hash",
	"submit/coinbase",
	"submit/truncate",
	"share/coinbase",
	"share/merkle",
	"share/truncate",
};

const char *blktmpl_capabilityname(gbt_capabilities_t caps) {
	for (unsigned int i = 0; i < GBT_CAPABILITY_COUNT; ++i)
		if (caps & (1 << i))
			return capnames[i];
	return NULL;
}

gbt_capabilities_t blktmpl_getcapability(const char *n) {
	for (unsigned int i = 0; i < GBT_CAPABILITY_COUNT; ++i)
		if (capnames[i] && !strcasecmp(n, capnames[i]))
			return 1 << i;
	return 0;
}

blktemplate_t *blktmpl_create() {
	blktemplate_t *tmpl;
	tmpl = calloc(1, sizeof(*tmpl));
	if (!tmpl)
		return NULL;
	
	tmpl->sigoplimit = USHRT_MAX;
	tmpl->sizelimit = ULONG_MAX;
	
	tmpl->maxtime = 0xffffffff;
	tmpl->maxtimeoff = 0x7fff;
	tmpl->mintimeoff = -0x7fff;
	tmpl->maxnonce = 0xffffffff;
	tmpl->expires = 0x7fff;
	
	return tmpl;
}

gbt_capabilities_t blktmpl_addcaps(const blktemplate_t *tmpl) {
	// TODO: make this a lot more flexible for merging
	// For now, it's a simple "filled" vs "not filled"
	if (tmpl->version)
		return 0;
	return GBT_CBTXN | GBT_WORKID | BMM_TIMEINC | BMM_CBAPPEND | BMM_VERFORCE | BMM_VERDROP | BMAb_COINBASE | BMAb_TRUNCATE;
}

const struct blktmpl_longpoll_req *blktmpl_get_longpoll(blktemplate_t *tmpl) {
	if (!tmpl->lp.id)
		return NULL;
	return &tmpl->lp;
}

bool blktmpl_get_submitold(blktemplate_t *tmpl) {
	return tmpl->submitold;
}

void _blktxn_free(struct blktxn_t *bt) {
	free(bt->data);
	free(bt->hash);
	free(bt->hash_);
	free(bt->depends);
}
#define blktxn_free  _blktxn_free

void blktmpl_free(blktemplate_t *tmpl) {
	for (unsigned long i = 0; i < tmpl->txncount; ++i)
		blktxn_free(&tmpl->txns[i]);
	free(tmpl->txns);
	if (tmpl->cbtxn)
	{
		blktxn_free(tmpl->cbtxn);
		free(tmpl->cbtxn);
	}
	free(tmpl->_mrklbranch);
	// TODO: maybe free auxnames[0..n]? auxdata too
	free(tmpl->auxnames);
	free(tmpl->auxdata);
	free(tmpl->workid);
	free(tmpl->lp.id);
	free(tmpl->lp.uri);
	free(tmpl);
}
