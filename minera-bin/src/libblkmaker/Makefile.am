# Copyright 2012-2013 Luke Dashjr
#
# This program is free software; you can redistribute it and/or modify it
# under the terms of the standard MIT license.  See COPYING for more details.

lib_LTLIBRARIES = \
	libblkmaker_jansson-@LIBBLKMAKER_API_VERSION@.la \
	libblkmaker-@LIBBLKMAKER_API_VERSION@.la


libblkmaker_@LIBBLKMAKER_API_VERSION@_la_SOURCES = \
	base58.c \
	blkmaker.c \
	blktemplate.c \
	hex.c

libblkmaker_@LIBBLKMAKER_API_VERSION@_la_LDFLAGS = -version-info $(LIBBLKMAKER_SO_VERSION) -no-undefined

libblkmaker_includedir = $(includedir)/libblkmaker-$(LIBBLKMAKER_API_VERSION)
libblkmaker_include_HEADERS = \
	blkmaker.h \
	blktemplate.h

libblkmaker_jansson_@LIBBLKMAKER_API_VERSION@_la_SOURCES = blkmaker_jansson.c
libblkmaker_jansson_@LIBBLKMAKER_API_VERSION@_la_DEPENDENCIES = libblkmaker-$(LIBBLKMAKER_API_VERSION).la
libblkmaker_jansson_@LIBBLKMAKER_API_VERSION@_la_CFLAGS = $(JANSSON_CFLAGS)
libblkmaker_jansson_@LIBBLKMAKER_API_VERSION@_la_LDFLAGS = \
	-L.libs \
	-lblkmaker-$(LIBBLKMAKER_API_VERSION) \
	$(JANSSON_LIBS) \
	-no-undefined \
	-version-info $(LIBBLKMAKER_SO_VERSION)

libblkmaker_jansson_includedir = $(includedir)/libblkmaker-$(LIBBLKMAKER_API_VERSION)
libblkmaker_jansson_include_HEADERS = \
	blkmaker_jansson.h

pkgconfigdir = $(libdir)/pkgconfig
pkgconfig_DATA = \
	libblkmaker_jansson-$(LIBBLKMAKER_API_VERSION).pc

dist_noinst_SCRIPTS = autogen.sh
