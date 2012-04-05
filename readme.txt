=== WP-Offline ===
Contributors: Bueltge
Donate link: http://bueltge.de/wunschliste/
Tags: offline
Requires at least: 3.0
Tested up to: 3.3
Stable tag: 0.8

Deactivate autoupdate for core, plugins, themes and http calls

== Description ==
Deactivate autoupdate for core, plugins,themes and http calls

See also the [Repo](https://github.com/bueltge/WP-Offline/) on github for issues and fork it for your improvements; also you can see an detailed [changelog](https://github.com/bueltge/WP-Offline/commits/master) from the commits.

== Installation ==
1. Unpack the download-package
1. Upload all files to the `/wp-content/plugins/` directory, include folders
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Add the new Template Tags to your templates

== Changelog ==
= v0.8 (04/05/2012) =
* Add filter `pre_http_request` for disable http calls

= v0.7 (02/11/2011)=
* Feature: disable cron via constant

= v0.6 (01/06/2011) =
* Feature: block external URL requests 
* Feature: disable transports

= v0.5 (01/04/2011) =
* Maintenance: update for WP 3.0, WP 3.1-RC2

= v0.1 (08/11/2008) =
* first version for work offline with WP and dont see different warnings, timeouts and errors
