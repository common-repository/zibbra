#!/bin/sh
xgettext -c -o zibbra.pot \
	--keyword="_" \
	--keyword="_e" \
	--keyword="Zibbra._" \
	--keyword="__" \
	../*.php \
	../core/*.php \
	../jscripts/account.js \
	../jscripts/album.js \
	../jscripts/bpost.js \
	../jscripts/cart.js \
	../jscripts/catalog.js \
	../jscripts/catalog.filters.js \
	../jscripts/checkout.js \
	../jscripts/payment.js \
	../jscripts/product.js \
	../jscripts/register.js \
	../jscripts/reset.js \
	../jscripts/widget_*.js \
	../jscripts/zibbra.js \
	../modules/*.php \
	../tags/*.php \
	../templates/*.php \
	../widgets/*.php
