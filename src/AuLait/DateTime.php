<?php
namespace AuLait;

use AuLait\Exception\DateTimeException;

/**
 * Class DateTime
 * @package AuLait
 */
class DateTime
{
    protected $timing = null;

    // TODO: timezone対応
    // protected $timezone = null;

    /**
     * @param $timing
     * @return $this
     * @throws DateTimeException
     */
    public function setTiming($timing)
    {
        $now = @strtotime($timing);
        if ($now === false) {
            throw new DateTimeException(
                'Illegal timing format.',
                DateTimeException::CODE_ILLEGAL_TIMING_FORMAT
            );
        }
        $this->timing = $timing;
        return $this;
    }

    /**
     * @return int
     */
    public function time()
    {
        if (is_null($this->timing)) {
            return time();
        }
        return strtotime($this->timing);
    }

    /**
     * @param $format
     * @return bool|string
     */
    public function date($format)
    {
        return date($format, $this->time());
    }
}
