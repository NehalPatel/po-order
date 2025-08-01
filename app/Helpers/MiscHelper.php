<?php

namespace App\Helpers;

class MiscHelper
{
    /**
     * Convert amount to words with dynamic currency support
     * 
     * @param float $amount The amount to convert
     * @param string $currency The currency name (default: 'Rupees')
     * @param string $suffix The suffix to add (default: 'Only')
     * @return string
     */
    public static function amountToWords($amount, $currency = 'Rupees', $suffix = 'Only')
    {
        // Handle zero amount
        if ($amount == 0) {
            return "Zero {$currency} {$suffix}";
        }

        // Handle negative amounts
        $isNegative = $amount < 0;
        $amount = abs($amount);

        // Split amount into rupees and paise
        $rupees = (int) $amount;
        $paise = round(($amount - $rupees) * 100);

        $words = '';

        // Convert rupees to words
        if ($rupees > 0) {
            $words = self::numberToWords($rupees) . ' ' . $currency;
        }

        // Convert paise to words
        if ($paise > 0) {
            $paiseWords = self::numberToWords($paise);
            if ($rupees > 0) {
                $words .= ' and ';
            }
            $words .= $paiseWords . ' Paise';
        }

        // Add suffix
        if (!empty($words)) {
            $words .= ' ' . $suffix;
        }

        // Add negative prefix if needed
        if ($isNegative) {
            $words = 'Negative ' . $words;
        }

        return ucwords($words);
    }

    /**
     * Convert number to words (Indian numbering system)
     * 
     * @param int $number
     * @return string
     */
    private static function numberToWords($number)
    {
        if ($number == 0) {
            return 'Zero';
        }

        $ones = [
            0 => '', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four',
            5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen',
            14 => 'Fourteen', 15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen',
            18 => 'Eighteen', 19 => 'Nineteen'
        ];

        $tens = [
            2 => 'Twenty', 3 => 'Thirty', 4 => 'Forty', 5 => 'Fifty',
            6 => 'Sixty', 7 => 'Seventy', 8 => 'Eighty', 9 => 'Ninety'
        ];

        $places = [
            1 => 'Thousand', 2 => 'Lakh', 3 => 'Crore', 4 => 'Arab',
            5 => 'Kharab', 6 => 'Neel', 7 => 'Padma', 8 => 'Shankh'
        ];

        $words = '';

        // Handle numbers less than 100
        if ($number < 100) {
            return self::convertLessThanHundred($number, $ones, $tens);
        }

        // Handle numbers 100 and above
        $placeIndex = 0;
        $lastTwoDigits = $number % 100;

        while ($number > 0) {
            $currentGroup = $number % 1000;
            $number = (int) ($number / 1000);

            if ($currentGroup > 0) {
                $groupWords = self::convertLessThanThousand($currentGroup, $ones, $tens);
                
                if ($placeIndex > 0) {
                    $groupWords .= ' ' . $places[$placeIndex];
                }
                
                if (!empty($words)) {
                    $words = $groupWords . ' ' . $words;
                } else {
                    $words = $groupWords;
                }
            }

            $placeIndex++;
        }

        return $words;
    }

    /**
     * Convert numbers less than 100
     * 
     * @param int $number
     * @param array $ones
     * @param array $tens
     * @return string
     */
    private static function convertLessThanHundred($number, $ones, $tens)
    {
        if ($number < 20) {
            return $ones[$number];
        }

        $ten = (int) ($number / 10);
        $one = $number % 10;

        $word = $tens[$ten];
        if ($one > 0) {
            $word .= ' ' . $ones[$one];
        }

        return $word;
    }

    /**
     * Convert numbers less than 1000
     * 
     * @param int $number
     * @param array $ones
     * @param array $tens
     * @return string
     */
    private static function convertLessThanThousand($number, $ones, $tens)
    {
        if ($number < 100) {
            return self::convertLessThanHundred($number, $ones, $tens);
        }

        $hundred = (int) ($number / 100);
        $remainder = $number % 100;

        $word = $ones[$hundred] . ' Hundred';
        if ($remainder > 0) {
            $word .= ' ' . self::convertLessThanHundred($remainder, $ones, $tens);
        }

        return $word;
    }

    /**
     * Format currency amount with proper formatting
     * 
     * @param float $amount
     * @param string $currency
     * @param int $decimals
     * @return string
     */
    public static function formatCurrency($amount, $currency = '₹', $decimals = 2)
    {
        return $currency . number_format($amount, $decimals);
    }

    /**
     * Generate a random string
     * 
     * @param int $length
     * @param string $type (alphanumeric, alphabetic, numeric)
     * @return string
     */
    public static function generateRandomString($length = 10, $type = 'alphanumeric')
    {
        $characters = '';
        
        switch ($type) {
            case 'alphabetic':
                $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                break;
            case 'numeric':
                $characters = '0123456789';
                break;
            case 'alphanumeric':
            default:
                $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                break;
        }

        $string = '';
        $max = strlen($characters) - 1;
        
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[random_int(0, $max)];
        }

        return $string;
    }

    /**
     * Format file size in human readable format
     * 
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    public static function formatFileSize($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Get initials from name
     * 
     * @param string $name
     * @param int $maxInitials
     * @return string
     */
    public static function getInitials($name, $maxInitials = 2)
    {
        $words = explode(' ', trim($name));
        $initials = '';

        for ($i = 0; $i < min(count($words), $maxInitials); $i++) {
            if (!empty($words[$i])) {
                $initials .= strtoupper(substr($words[$i], 0, 1));
            }
        }

        return $initials;
    }

    /**
     * Mask sensitive data (like credit card numbers)
     * 
     * @param string $data
     * @param string $mask
     * @param int $start
     * @param int $end
     * @return string
     */
    public static function maskData($data, $mask = '*', $start = 0, $end = null)
    {
        $length = strlen($data);
        
        if ($end === null) {
            $end = $length - 4;
        }

        if ($start >= $length || $end <= $start) {
            return $data;
        }

        $masked = substr($data, 0, $start);
        $masked .= str_repeat($mask, $end - $start);
        $masked .= substr($data, $end);

        return $masked;
    }

    /**
     * Validate Indian phone number
     * 
     * @param string $phone
     * @return bool
     */
    public static function isValidIndianPhone($phone)
    {
        // Remove spaces, dashes, and plus sign
        $phone = preg_replace('/[\s\-+]/', '', $phone);
        
        // Check if it starts with 91 (country code) or 6-9 (mobile prefix)
        return preg_match('/^(91)?[6-9]\d{9}$/', $phone);
    }

    /**
     * Validate Indian PAN number
     * 
     * @param string $pan
     * @return bool
     */
    public static function isValidPAN($pan)
    {
        return preg_match('/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/', strtoupper($pan));
    }

    /**
     * Validate Indian Aadhaar number
     * 
     * @param string $aadhaar
     * @return bool
     */
    public static function isValidAadhaar($aadhaar)
    {
        // Remove spaces and dashes
        $aadhaar = preg_replace('/[\s\-]/', '', $aadhaar);
        
        return preg_match('/^[0-9]{12}$/', $aadhaar);
    }

    /**
     * Get age from date of birth
     * 
     * @param string $dateOfBirth
     * @return int
     */
    public static function getAge($dateOfBirth)
    {
        $dob = new \DateTime($dateOfBirth);
        $now = new \DateTime();
        $interval = $now->diff($dob);
        
        return $interval->y;
    }

    /**
     * Calculate percentage
     * 
     * @param float $value
     * @param float $total
     * @param int $decimals
     * @return float
     */
    public static function calculatePercentage($value, $total, $decimals = 2)
    {
        if ($total == 0) {
            return 0;
        }
        
        return round(($value / $total) * 100, $decimals);
    }
} 