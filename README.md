# Brew Competition Online Entry & Management

Working repository of BCOE&M. 

Website: http://www.brewcompetition.com | [Installation Instructions] (http://www.brewcompetition.com/install-instructions) | [Upgrade Instructions] (http://www.brewcompetition.com/upgrade-instructions)

The Brew Competition Online Entry and Management (BCOE&M) system is an online application to assist homebrew competition organizers - of the beer/mead/cider variety - to collect, store, and manage their competition entry, organization, and scoring data.

The biggest challenges of organizing a homebrewing competition is knowing who has entered what and how many, organizing judging efficiently, and reporting the results of the competition in a timely manner. BCOE&M provides a single online interface to collect entry and participant data, organize judging tables and assignments, input scoring data, and report the results.

The best part: **BCOE&M is free and open-source**. Hundreds of competitions have utilized BCOE&M since its [first release] (http://brewcompetition.com/change-log) back in 2009.

## Download
Version 2.1.X is now available for [download here on GitHub] (https://github.com/geoffhumphrey/brewcompetitiononlineentry/releases). The [latest committed code](https://github.com/geoffhumphrey/brewcompetitiononlineentry/archive/master.zip) is also available for testers and contributors.

## Install or Upgrade
Step by step [installation] (http://www.brewcompetition.com/install-instructions) and [upgrade] (http://www.brewcompetition.com/upgrade-instructions) instructions are available.

After configuration to your environment, installation is a breeze via the online setup interface. 

## Fallback Installation
There are times when the online setup encounters issues that prevent the installation from successfully completing. That's why there's a [Fallback Installation] (http://brewcompetition.com/install-instructions#fallback) method. For those experiencing any issues related to the initial browser-based setup, the bcoem_baseline_2.1.X.sql document is available in the package's /sql/ folder. This document contains the necessary database structure and dummy data for a new installation that can be installed manually via phpMyAdmin or shell access. Be sure to follow the directions in the document **BEFORE** use.

## Issue Reporting and Bug Fixes
Many bugs and issues reported to this repository are corrected before an official release is available. Before reporting a bug, be sure to check the [Issues] (https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues) list to see if it has been addressed already. If it has, chances are the latest commit package contains code to fix the issue. Keep an eye out for the [*fixed in latest master commit*] (https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues?q=is%3Aissue+is%3Aopen+label%3A%22fixed+in+latest+master+commit%22) tag. Needless to say, however, the master and other branch commits housed here in the repository are **NOT FOR PRODUCTION**! Bugs may be present. 

## Disable CDN and Load Libraries Locally
Refer to [these instructions] (http://brewcompetition.com/local-load) to if you do wish to use local libraries in favor of CDN.

## Wanna Help?
Fork and help out with the development!
