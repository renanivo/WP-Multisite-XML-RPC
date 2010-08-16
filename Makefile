dependencies: mockpress
	pear channel-discover pear.phpunit.de
	pear install phpunit/PHPUnit

mockpress:
	pear upgrade -f http://www.coswellproductions.com/mockpress/pear/latest.tgz
