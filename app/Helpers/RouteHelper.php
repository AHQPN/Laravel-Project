<?php

if (!function_exists('shortenCityName')) {
    function shortenCityName($cityName)
    {
        $cityName = trim($cityName);
        
        // Mapping các tên thành phố dài sang viết tắt
        $cityMap = [
            'TP. Ho Chi Minh' => 'HCM',
            'TP. Hồ Chí Minh' => 'HCM',
            'Ho Chi Minh' => 'HCM',
            'Hồ Chí Minh' => 'HCM',
            'Tp. Ho Chi Minh' => 'HCM',
            'Can Tho' => 'Cần Thơ',
            'Hai Phong' => 'Hải Phòng',
            'Da Nang' => 'Đà Nẵng',
            'Da Lat' => 'Đà Lạt',
            'Vung Tau' => 'Vũng Tàu',
            'Nha Trang' => 'Nha Trang',
            'Ha Noi' => 'Hà Nội',
        ];
        
        return $cityMap[$cityName] ?? $cityName;
    }
}

if (!function_exists('formatRouteForDropdown')) {
    function formatRouteForDropdown($routeName)
    {
        $parts = preg_split('/\s*(?:→|->|-)\s*/u', $routeName);
        if (count($parts) < 2) {
            return $routeName;
        }
        
        $from = shortenCityName(trim($parts[0]));
        $to = shortenCityName(trim($parts[1]));
        
        return "$from → $to";
    }
}
