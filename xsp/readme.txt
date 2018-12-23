=============================
	** READ ME **
=============================

This software is an extension of / plugin for PHP and XPath that hopes to revolutionize the way we use XML files by using MySQL-like queries to save and retrieve data. This file and subsequently the header within it must stay in tact at all times for it to be a valid copy of this software. Extensive Server Path, or XSP, is copyright to Paul Timothy Shannon Jr, a.k.a Zollern Wolf, (c) 2012 - Present, where Present is the current month and year.

** Please do not claim this as your own, as I have screenshots and the exact date when I started the project. I will file charges if credit is taken for my work. This license may not change, and this copyright cannot be removed. If either of these are disputed, then this copy is no longer a legal or valid one.

** IF YOU MAKE ANY CHANGES TO THE CODE AND WISH TO UPLOAD IT SEPARATELY, then please reference my original project via hyperlink. I believe in "plugins" and "libraries" and I highly encourage them, but you MUST link back to this project as it is the main source. Do not claim yours as the "official" version, as that is what mine is. If I happen to see a change I wish to implement into a newer version, then I will attempt to contact you to discuss it, so please leave some way for me to contact you as well. This is for your benefit, as I believe in teamwork very heavily. I do not, however, believe in theft. Thank you.

=============================
	** USAGE **
=============================

To start, you must first initiate the class file:

<?php
include 'xsp.php';
$xsp = new XSP();

//Complex Variables
$xsp->Parse('var set str = "Hello";');
$xsp->Parse("out var(str);");
?>

Documentation is included within a text file in this package, as well as a beginner's guide. More often than not, however, I will make changes and forget to include them in the Documentation or in the Tutorials. If I fall behind on this I do apologize, I have many other responsibilities right now. I will get to them when I get a chance, or you can pick up on my slack if you like.

Refer to the example files to better understand the setup. Note that when querying an element, XPath MUST be used. A decent knowledge of PHP and its OOP format is highly recommended. To use the PHP portion, please ensure that a server is installed on your system, e.g. WAMPServer (for Windows) or MAMPServer (for Macs).


** FOR THOSE WHO USE NOTEPAD++: There is a courtesy XSP language definition that can be applied to your .xsp files for syntax highlighting. Simply select Define Your Language from the Language menu, and then click on "Import". Navigate to THIS xsp folder (or rather, the most recent one you downloaded, as version updates are frequent), and then type XSP into the search bar. For whatever reason, it does NOT appear in the visible file dialog - you MUST type in XSP (no extension, casing shouldn't matter but I do it anyway). Once you have it selected, you may close the Define Your Language menu. Open the Language drop-down menu again, and "XSP" should be listed near the very bottom. Again, updates are frequent, so be sure to check REGULARLY! **

HOME SITE: http://xsp.zollernverse.org/

NOTE: If the site defaults to http://www.zollernverse.org/ (or doesn't load at all), it's because the XSP home page isn't up yet. The home page URL is simply a placeholder - this file will be updated once the site is officially launched.

==========================
** User Contributions **
==========================

	Please, if you feel you can add something to XSP, I would be most grateful for it - just leave a changelog and comments explaning A.) What you've changed/added and B.) How the change/addition works. In order for me to produce stable versions in the future, I must understand what about the language you have modded. If you would like to publicize your contribution officially (as in have me put it up as a downloadable library/replacement [depending]), then please e-mail me at admin@zollernverse.org. I will be more than happy to discuss collaborating with you. However, my e-mail is NOT for you to complain to me or bug me 24/7. That's what the Discussions page on SourceForge is for, as well as the future Support Forums on the XSP home site (once it is finally up).

	That being said, I hope developers get a good use out of this. I know it's not too much right now, but with months and years of work, I know it will be a powerful addition, not only to PHP, but hopefully to other languages as well, such as Java (God help me when I get there, but I'm gonna try). This language/library has a lot of potential, there just has to be a lot of effort and use. :)

Thank you for downloading and trying it out!

-- Paul T. Shannon Jr. a.k.a Zollern Wolf