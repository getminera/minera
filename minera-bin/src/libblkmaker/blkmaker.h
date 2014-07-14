#ifndef BLKMAKER_H
#define BLKMAKER_H

#include <stdbool.h>
#include <stdint.h>
#include <unistd.h>

#include <blktemplate.h>

#define BLKMAKER_VERSION (5L)
#define BLKMAKER_MAX_BLOCK_VERSION (2)

extern bool (*blkmk_sha256_impl)(void *hash_out, const void *data, size_t datasz);

extern uint64_t blkmk_init_generation(blktemplate_t *, void *script, size_t scriptsz);
extern uint64_t blkmk_init_generation2(blktemplate_t *, void *script, size_t scriptsz, bool *out_newcb);
extern uint64_t blkmk_init_generation3(blktemplate_t *, const void *script, size_t scriptsz, bool *inout_newcb);
extern ssize_t blkmk_append_coinbase_safe(blktemplate_t *, const void *append, size_t appendsz);
extern ssize_t blkmk_append_coinbase_safe2(blktemplate_t *, const void *append, size_t appendsz, int extranoncesz, bool merkle_only);
extern bool _blkmk_extranonce(blktemplate_t *tmpl, void *vout, unsigned int workid, size_t *offs);
extern size_t blkmk_get_data(blktemplate_t *, void *buf, size_t bufsz, time_t usetime, int16_t *out_expire, unsigned int *out_dataid);
extern bool blkmk_get_mdata(blktemplate_t *, void *buf, size_t bufsz, time_t usetime, int16_t *out_expire, void *out_cbtxn, size_t *out_cbtxnsz, size_t *cbextranonceoffset, int *out_branchcount, void *out_branches, size_t extranoncesz, bool can_roll_ntime);
extern blktime_diff_t blkmk_time_left(const blktemplate_t *, time_t nowtime);
extern unsigned long blkmk_work_left(const blktemplate_t *);
#define BLKMK_UNLIMITED_WORK_COUNT  ULONG_MAX

extern size_t blkmk_address_to_script(void *out, size_t outsz, const char *addr);

#endif
