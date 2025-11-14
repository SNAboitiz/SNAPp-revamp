<?php

namespace App\Services;

use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\Log;

class GcsService
{
    private ?StorageClient $storageClient = null;

    private array $config;

    private bool $isInitialized = false;

    /**
     * The constructor now ONLY stores the config.
     * It does NOT try to connect to Google, which fixes the startup crash.
     */
    public function __construct()
    {
        $this->config = config('services.google_cloud');
    }

    /**
     * This private method initializes the GCS client ONCE, the first time it's needed.
     * This is the "lazy loading" part.
     */
    private function initializeClient(): bool
    {
        // If we have already tried to connect, don't try again.
        if ($this->isInitialized) {
            return ! is_null($this->storageClient);
        }

        // Mark that we are now attempting to initialize.
        $this->isInitialized = true;

        if (empty($this->config['project_id']) || empty($this->config['key_file']) || empty($this->config['bucket'])) {
            Log::critical('GCS FATAL ERROR: Configuration keys are missing in config/services.php or .env file.');

            return false;
        }

        try {
            // The actual connection to Google happens HERE.
            // If the key file path is invalid, this will throw a clear exception.
            $this->storageClient = new StorageClient([
                'projectId' => $this->config['project_id'],
                'keyFilePath' => $this->config['key_file'],
            ]);

            return true;
        } catch (\Exception $e) {
            Log::critical('GCS FATAL ERROR: Failed to instantiate Storage client. Check key file path and permissions.', [
                'key_file_path' => $this->config['key_file'],
                'exception_message' => $e->getMessage(),
            ]);
            $this->storageClient = null;

            return false;
        }
    }

    /**
     * Generates a temporary signed URL for a given GCS object path.
     */
    public function generateSignedUrl(string $objectPath): ?string
    {
        // First, ensure the client is ready by calling our new helper method.
        if (! $this->initializeClient()) {
            Log::error('GCS Error: Cannot generate URL because client failed to initialize.');

            return null;
        }

        try {
            $bucket = $this->storageClient->bucket($this->config['bucket']);
            $object = $bucket->object($objectPath);

            if (! $object->exists()) {
                Log::info("GCS Info: Object not found: gs://{$this->config['bucket']}/{$objectPath}");

                return null;
            }

            return $object->signedUrl(new \DateTime('15 min'), ['version' => 'v4']);
        } catch (\Exception $e) {
            Log::error('GCS Error: Could not generate signed URL. This is likely a PERMISSION ERROR (missing Service Account Token Creator role).', [
                'object_path' => "gs://{$this->config['bucket']}/{$objectPath}",
                'exception' => $e->getMessage(),
            ]);

            return null;
        }
    }

    public function uploadLaravelFile(UploadedFile $uploadedFile, string $destinationObjectName, array $options = []): ?string
    {
        // Ensure the GCS client is initialized before attempting to upload.
        if (! $this->initializeClient()) {
            Log::error('GCS Error: Cannot upload file because client failed to initialize.');

            return null;
        }

        try {
            // Get the bucket object.
            $bucket = $this->storageClient->bucket($this->config['bucket']);

            // Open a read stream to the temporary file Laravel stores for uploaded files.
            $fileStream = fopen($uploadedFile->getPathname(), 'r');
            if (! $fileStream) {
                Log::error('GCS Error: Could not open uploaded file stream for: '.$uploadedFile->getPathname());

                return null;
            }

            // Prepare upload options.
            // 'name' is the destination path in GCS.
            // 'contentType' is crucial for browsers to correctly interpret the file.
            $uploadOptions = [
                'name' => $destinationObjectName,
                'contentType' => $uploadedFile->getMimeType(),
            ];

            // Merge any additional options provided (e.g., 'predefinedAcl', 'metadata').
            if (! empty($options)) {
                $uploadOptions = array_merge($uploadOptions, $options);
            }

            // Perform the file upload to GCS.
            $object = $bucket->upload($fileStream, $uploadOptions);

            // Close the file stream after the upload is complete.
            fclose($fileStream);

            Log::info("GCS Info: File uploaded successfully to: gs://{$this->config['bucket']}/{$destinationObjectName}");

            // The mediaLink is a direct URL to the object. Its public accessibility
            // depends on bucket/object ACLs (e.g., if 'predefinedAcl' => 'publicRead' was set).
            // For private files, you would still use generateSignedUrl to provide temporary access.
            return $object->info()['mediaLink'] ?? null;

        } catch (\Exception $e) {
            // Log detailed error if upload fails, indicating possible permission issues or file problems.
            Log::error("GCS Error: Failed to upload file '{$destinationObjectName}'. Check permissions (e.g., 'Storage Object Creator' role) and file integrity.", [
                'destination_object' => "gs://{$this->config['bucket']}/{$destinationObjectName}",
                'exception_message' => $e->getMessage(),
                'exception_trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }
}
