--TEST--
PEAR_Downloader_Package->initialize() with package.xml
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$pathtopackagexml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'test_initialize_invalidpackagexml'. DIRECTORY_SEPARATOR . 'invalid.xml';
$dp = &newDownloaderPackage(array());
$phpunit->assertNoErrors('after create');
$message = version_compare(phpversion(), '5.0.0', '<') ?
    'XML error: not well-formed (invalid token) at line 1' :
    'XML error: Empty document at line 1';

$result = $dp->initialize($pathtopackagexml);
$phpunit->assertErrors(
    array(
        'package' => 'PEAR_Error',
        'message' => "Cannot initialize '$pathtopackagexml', invalid or missing package file"),
    'after initialize');
$phpunit->assertEquals(array(
    array(0, $message)),
    $fakelog->getLog(), 'after initialize log');
$phpunit->assertIsa('PEAR_Error', $result, 'no error returned');
$phpunit->assertEquals("Cannot initialize '$pathtopackagexml', invalid or missing package file", $result->getMessage(), 'wrong error message');


$pathtopackagexml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'test_initialize_invalidpackagexml'. DIRECTORY_SEPARATOR . 'package.xml';
$dp = &newDownloaderPackage(array());
$phpunit->assertNoErrors('after create');
$result = $dp->initialize($pathtopackagexml);
$phpunit->assertErrors(array(
    array('package' => 'PEAR_PackageFile_v1', 'message' => 'Missing Package Name'),
    array('package' => 'PEAR_Error', 'message' =>
        "Cannot initialize '$pathtopackagexml', invalid or missing package file")),
        'after initialize');
$phpunit->assertEquals(array(
    array(0, 'Missing Package Name'),
    array(0, 'Parsing of package.xml from file "' . $pathtopackagexml .
        '" failed')), $fakelog->getLog(), 'after initialize log');
$phpunit->assertIsa('PEAR_Error', $result, 'no error returned');
$phpunit->assertEquals("Cannot initialize '$pathtopackagexml', invalid or missing package file",
    $result->getMessage(), 'wrong error message');


$pathtopackagexml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'test_initialize_invalidpackagexml'. DIRECTORY_SEPARATOR . 'test-1.0.tgz';
$dp = &newDownloaderPackage(array());
$phpunit->assertNoErrors('after create');
$result = $dp->initialize($pathtopackagexml);
$phpunit->assertErrors(array(
    array(
        'package' => 'PEAR_Error',
        'message' => "Cannot initialize '$pathtopackagexml', invalid or missing package file"
    )
), 'after initialize');

$phpunit->assertEquals(array(
    array(
        0,
        'could not extract the package.xml file from "' . $pathtopackagexml . '"')
    ),
    array (
        0 => 2,
        1 => 'Cannot initialize \'/media/Stewie/projects/pear/pear-core/tests/PEAR_Downloader_Package/test_initialize_invalidpackagexml/invalid.xml\', invalid or missing package file',
    ),
$fakelog->getLog(), 'after initialize log');
$phpunit->assertIsa('PEAR_Error', $result, 'no error returned');
$phpunit->assertEquals("Cannot initialize '$pathtopackagexml', invalid or missing package file",
    $result->getMessage(), 'wrong error message');
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(__FILE__) . '/teardown.php.inc';
?>
--EXPECT--
tests done
