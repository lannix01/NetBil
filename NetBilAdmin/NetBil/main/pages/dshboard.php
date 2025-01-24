<?php

require_once 'vendor/autoload.php';

use PEAR2\Net\RouterOS\Client;
use PEAR2\Net\RouterOS\Request;
use PEAR2\Net\RouterOS\Response;

class MikrotikDashboard {
    private $mikrotik_ip;
    private $mikrotik_username;
    private $mikrotik_password;

    public function __construct($mikrotik_ip, $mikrotik_username, $mikrotik_password) {
        $this->mikrotik_ip = $mikrotik_ip;
        $this->mikrotik_username = $mikrotik_username;
        $this->mikrotik_password = $mikrotik_password;
    }

    public function getTransmissionData() {
        try {
            $client = new Client($this->mikrotik_ip, $this->mikrotik_username, $this->mikrotik_password);
            $request = new Request('/interface/print');
            $response = $client->sendSync($request);

            return $this->formatData($response);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return null;
        }
    }

    private function formatData($response) {
        $formattedData = [];
        foreach ($response as $interface) {
            $name = $interface('name');
            $txBytes = (int)$interface('tx-bytes');
            $rxBytes = (int)$interface('rx-bytes');
            $uptime = $interface('uptime');

            $formattedData[] = [
                "interface" => $name,
                "tx" => $this->formatBytes($txBytes),
                "rx" => $this->formatBytes($rxBytes),
                "uptime" => $uptime,
            ];
        }
        return $formattedData;
    }

    private function formatBytes($bytesValue) {
        $units = ["B", "KB", "MB", "GB", "TB"];
        $i = 0;
        while ($bytesValue >= 1024 && $i < count($units) - 1) {
            $bytesValue /= 1024;
            $i++;
        }
        return sprintf("%.2f %s", $bytesValue, $units[$i]);
    }
}

// Usage:
$mikrotik = new MikrotikDashboard("192.168.1.1", "admin", "your_password_here");
$data = $mikrotik->getTransmissionData();

if ($data) {
    foreach ($data as $entry) {
        echo "Interface: {$entry['interface']}\n";
        echo "TX: {$entry['tx']}\n";
        echo "RX: {$entry['rx']}\n";
        echo "Uptime: {$entry['uptime']}\n";
        echo "--------------------------\n";
    }
}
?>
