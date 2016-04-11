<?php
namespace AuLait;
use AuLait\Exception\ImageException;

/**
 * Class Image
 * @package AuLait
 */
class Image
{
    /**
     * @var string
     */
    protected $filename = null;

    /**
     * @var resource
     */
    protected $gd = null;

    /**
     * __constructor
     *
     * @param string|resource $filename
     * @throws ImageException
     */
    public function __construct($filename)
    {
        if (is_resource($filename)) {
            $this->gd = $filename;
        } elseif ($filename) {
            $contents = @file_get_contents($filename);
            if ($contents === false) {
                throw new ImageException(
                    "Failed to read $filename",
                    ImageException::CODE_FILED_TO_READ_FILE
                );
            }
            $this->gd = @imagecreatefromstring($contents);
            if (!$this->gd) {
                throw new ImageException(
                    "$filename is unsupported format",
                    ImageException::CODE_UNSUPPORTED_FORMAT
                );
            }
            $this->filename = $filename;
        }
    }

    /**
     * get image width
     */
    public function getWidth()
    {
        return imagesX($this->gd);
    }

    /**
     * get image height
     */
    public function getHeight()
    {
        return imagesY($this->gd);
    }

    /**
     * @param int $maxX
     * @param int $maxY
     * @param bool $ignoreAspectRatio 画像の縦横比を維持するか。
     * @return Image
     */
    public function resize($maxX = 180, $maxY = 180, $ignoreAspectRatio = false)
    {
        $width = $this->getWidth();
        $height = $this->getHeight();

        if ($ignoreAspectRatio === false) {
            $resizeX = $maxX;
            $resizeY = $maxY;
        } elseif ($width < $height) {
            $resizeX = ceil(($maxX * $width) / $height);
            $resizeY = $maxY;
        } else {
            $resizeX = $maxX;
            $resizeY = ceil(($maxY * $height) / $width);
        }

        // 透明色が指定されているとjpg保存時に透明色部が黒くなるのでその対策。
        // MEMO: PNGで保存するなら
        //       imagealphablending($resize, false);
        //       imagesavealpha($resize, true);
        $resizeGD = @imageCreateTrueColor($resizeX, $resizeY);
        $alpha = imagecolortransparent($this->gd);
        if ($alpha !== -1) {
            $color = imagecolorallocate($resizeGD, 255, 255, 255);
            imageFill($resizeGD, 0, 0, $color);
            imageColorTransparent($resizeGD, $alpha);
        } else {
            imageAlphaBlending($resizeGD, true);
            imageSaveAlpha($resizeGD, true);
            $fill_color = imagecolorallocate($resizeGD, 255, 255, 255);
            imageFill($resizeGD, 0, 0, $fill_color);
        }

        imageCopyResampled($resizeGD, $this->gd, 0, 0, 0, 0, $resizeX, $resizeY, $width, $height);

        return new Image($resizeGD);
    }

    /**
     * @param string|null $filename
     * @param int $quality
     * @return bool
     * @throws \Exception
     */
    public function save($filename = null, $quality = 75)
    {
        if (is_null($filename) && is_null($this->filename)) {
            throw new ImageException(
                'File name is empty',
                ImageException::CODE_FILENAME_IS_EMPTY
            );
        }

        // TODO: ファイルの拡張子見てフォーマット決めるようにする。
        if (is_null($filename)) {
            return imagejpeg($this->gd, $this->filename, $quality);
        } else {
            $this->filename = $filename;
            return imagejpeg($this->gd, $filename, $quality);
        }
    }

    /**
     * @param int $quality
     * @return string
     */
    public function getBinary($quality = null)
    {
        ob_start();
        imagejpeg($this->gd, null, $quality);
        $binary = ob_get_contents();
        ob_end_clean();
        return $binary;
    }

    /**
     *
     */
    public function getExif()
    {
        if (is_null($this->filename)) {
            return null;
        }

        $result = @exif_read_data($this->filename, 'EXIF');
        if (!$result) {
            return null;
        }

        return $result;
    }
}
