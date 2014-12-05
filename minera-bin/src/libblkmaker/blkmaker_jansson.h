#ifndef BLKMAKER_JANSSON_H
#define BLKMAKER_JANSSON_H

#include <jansson.h>

#include <blktemplate.h>

#ifdef __cplusplus
extern "C" {
#endif

extern json_t *blktmpl_request_jansson(uint32_t extracaps, const char *lpid);
extern const char *blktmpl_add_jansson(blktemplate_t *, const json_t *, time_t time_rcvd);
extern json_t *blktmpl_propose_jansson(blktemplate_t *, uint32_t caps, bool foreign);
extern json_t *blkmk_submit_jansson(blktemplate_t *, const unsigned char *data, unsigned int dataid, blknonce_t);
extern json_t *blkmk_submit_foreign_jansson(blktemplate_t *, const unsigned char *data, unsigned int dataid, blknonce_t);

#ifdef __cplusplus
}
#endif

#endif
