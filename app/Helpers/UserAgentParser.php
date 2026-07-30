<?php

namespace App\Helpers;

class UserAgentParser
{
    private string $ua;

    public function __construct(?string $userAgent)
    {
        $this->ua = $userAgent ?? '';
    }

    public function browser(): string
    {
        if (preg_match('/Edg\/([\d.]+)/', $this->ua)) return 'Edge';
        if (preg_match('/OPR\/([\d.]+)/', $this->ua)) return 'Opera';
        if (preg_match('/Firefox\/([\d.]+)/', $this->ua)) return 'Firefox';
        if (preg_match('/Chrome\/([\d.]+)/', $this->ua)) return 'Chrome';
        if (preg_match('/Safari\/([\d.]+)/', $this->ua)) return 'Safari';
        if (preg_match('/MSIE ([\d.]+)/', $this->ua)) return 'IE';
        if (preg_match('/Trident\//', $this->ua)) return 'IE';
        return 'Unknown';
    }

    public function browserVersion(): string
    {
        $map = [
            'Edge'    => '/Edg\/([\d.]+)/',
            'Opera'   => '/OPR\/([\d.]+)/',
            'Firefox' => '/Firefox\/([\d.]+)/',
            'Chrome'  => '/Chrome\/([\d.]+)/',
            'Safari'  => '/Version\/([\d.]+)/',
            'IE'      => '/(?:MSIE |rv:)([\d.]+)/',
        ];
        $browser = $this->browser();
        if (isset($map[$browser]) && preg_match($map[$browser], $this->ua, $m)) {
            return $m[1];
        }
        return '';
    }

    public function platform(): string
    {
        if (stripos($this->ua, 'Windows')) return 'Windows';
        if (stripos($this->ua, 'Android')) return 'Android';
        if (stripos($this->ua, 'iPhone') !== false || stripos($this->ua, 'iPad') !== false) return 'iOS';
        if (stripos($this->ua, 'Linux')) return 'Linux';
        if (stripos($this->ua, 'Mac OS X')) return 'macOS';
        return 'Unknown';
    }

    public function device(): string
    {
        if (stripos($this->ua, 'Mobile') !== false || stripos($this->ua, 'Android') !== false) {
            if (preg_match('/; (\w+[^;]*?)(?: Build|\))/', $this->ua, $m)) return $m[1];
            return 'Mobile';
        }
        if (stripos($this->ua, 'iPad') !== false) return 'iPad';
        if (stripos($this->ua, 'iPhone') !== false) return 'iPhone';
        return 'Desktop';
    }
}
