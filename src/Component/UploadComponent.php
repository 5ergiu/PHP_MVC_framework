<?php

namespace App\Component;

/**
 * @property array|null $file The file to be uploaded.
 * @property array $allowedExtensions
 * @property bool $status
 * @property string|null $filename
 */
class UploadComponent
{
    private bool $status = false;
    private ?string $filename;

    /**
     * UploadComponent constructor.
     * @param array|null $file
     */
    public function __construct(
        private ?array $file
    ) {
        $this->__uploadFile();
    }

    private array $allowedExtensions = [
        'jpg',
        'jpeg',
        'png',
        'gif',
    ];

    /**
     * Uploads a file and updates the status.
     * @return void
     */
    private function __uploadFile(): void
    {
        $fileExtension = pathinfo($this->file['name'], PATHINFO_EXTENSION);
        if (in_array($fileExtension, $this->allowedExtensions)) {
            if ($this->file['error'] === 0) {
                if ($this->file['size'] <= 20971520) {
                    $this->filename = uniqid() . "-temp.$fileExtension";
                    $destination = UPLOADS . $this->filename;
                    if (move_uploaded_file($this->file['tmp_name'], $destination)) {
                        $this->status = true;
                    }
                }
            }
        }
    }

    /**
     * @return bool
     */
    public function getStatus(): bool
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }
}
