<?php
namespace AuLait\Test;

use AuLait\Image;

class ImageTest extends \PHPUnit\Framework\TestCase
{
    public function setUp(): void
    {
        $saveImg = 'tmp/saved.jpg';
        if (file_exists($saveImg)) {
            unlink($saveImg);
        }
    }

    /**
     * 画像ファイルが存在しない場合
     *
     * @expectedException \AuLait\Exception\ImageException
     * @expectedExceptionCode　\AuLait\Exception\ImageException::CODE_FILED_TO_READ_FILE
     */
    public function testInitializeFailed()
    {
        $image = new Image('fixture/no_exist.jpg');
    }

    /**
     * 画像ファイル以外を読み込んだ場合
     *
     * @expectedException \AuLait\Exception\ImageException
     * @expectedExceptionCode　\AuLait\Exception\ImageException::CODE_UNSUPPORTED_FORMAT
     */
    public function testInitializeFailed2()
    {
        $image = new Image('fixture/illegal.jpg');
    }

    static public function providerResize()
    {
        return [
            [
                'fixture/horizontal.jpg',
                true,
                50,
                50,
            ],
            [
                'fixture/horizontal.jpg',
                false,
                50,
                25,
            ],
            [
                'fixture/vertical.jpg',
                false,
                25,
                50,
            ]
        ];
    }

    /**
     * 縮小テスト
     *
     * @dataProvider providerResize
     * @param $filename
     * @param $ignoreAspectRatio
     * @param $expectedX
     * @param $expectedY
     */
    public function testImage($filename, $ignoreAspectRatio, $expectedX, $expectedY)
    {
        $image = new Image($filename);
        $resize = $image->resize(50, 50, $ignoreAspectRatio);
        $this->assertEquals($expectedX, $resize->getWidth());
        $this->assertEquals($expectedY, $resize->getHeight());
    }

    /**
     * 上書き保存
     */
    public function testSave()
    {
        $copyFile = 'tmp/copy.jpg';
        copy('fixture/vertical.jpg', $copyFile);
        $image = new Image($copyFile);
        unlink($copyFile);
        $image->save();
        $this->assertFileExists($copyFile);
    }

    /**
     * 名前をつけて保存
     */
    public function testSave2()
    {
        $saveFile = 'tmp/saved.jpg';

        $image = new Image('fixture/vertical.jpg');
        $image->save($saveFile);
        $this->assertFileExists($saveFile);
    }

    /**
     * リサイズ後、名前指定せずに保存
     *
     * @expectedException \AuLait\Exception\ImageException
     * @expectedExceptionCode　\AuLait\Exception\ImageException::CODE_FILENAME_IS_BLANK
     */
    public function testSaveFailed()
    {
        $image = new Image('fixture/vertical.jpg');
        $image->resize(80,80)->save();
    }

    /**
     * 画像データをバイナリで取得する
     */
    public function testGetBinary()
    {
        $resizeX = 50;
        $resizeY = 50;

        $image = new Image('fixture/horizontal.jpg');
        $resize = $image->resize($resizeX, $resizeY, true);
        $binary = $image->getBinary();

        $binaryGD = imagecreatefromstring($binary);
        $this->assertEquals($resizeX, $resize->getWidth());
        $this->assertEquals($resizeY, $resize->getHeight());
        imagedestroy($binaryGD);
    }

    static public function providerTransparent()
    {
        return [
            [
                'fixture/transparent.gif'
            ],
            [
                'fixture/transparent.png'
            ]
        ];
    }

    /**
     * JPEGとして保存すると透過色部分が黒くなってしまうので白くなっているかテスト。
     * @dataProvider providerTransparent
     */
    public function testTransparent($filename)
    {
        $saveFileName = 'tmp/saved.jpg';
        $image = new Image('fixture/transparent.gif');
        $image->resize(64, 64, true)->save('tmp/saved.jpg');

        $savedGD = imagecreatefromjpeg('tmp/saved.jpg');
        $white = 16777215; // (r, g, b) = (255,255,255)
        $this->assertEquals($white, imagecolorat($savedGD, 0,0));

        imagedestroy($savedGD);
    }

    /**
     * exifあり
     *
     * @requires extension exif
     */
    public function testExif()
    {
        $image = new Image('fixture/exif.jpg');
        $exif = $image->getExif();
        $this->assertEquals('AuLait', $exif['Make']);
    }

    /**
     * exifなし
     *
     * @requires extension exif
     */
    public function testExif2()
    {
        $image = new Image('fixture/vertical.jpg');
        $exif = $image->getExif();
        $this->assertEquals(null, $exif);
    }

    /**
     * exifなし(resize後のもの)
     *
     * @requires extension exif
     */
    public function testExif3()
    {
        $image = new Image('fixture/vertical.jpg');
        $exif = $image->resize()->getExif();
        $this->assertEquals(null, $exif);
    }
}
