<?php


namespace App\DataProviders;

class DataProviderFactory
{
    public function createDataProvider(string $providerName): DataProviderInterface
    {
        // Implement logic to create the appropriate DataProvider based on $providerName
        // For example:
        switch ($providerName) {
            case 'DataProviderW':
                return new DataProviderW();
            case 'DataProviderX':
                return new DataProviderX();
            case 'DataProviderY':
                return new DataProviderY();
            // Add more cases for other providers as needed
            default:
                throw new \InvalidArgumentException("Unknown provider: $providerName");
        }
    }
}
