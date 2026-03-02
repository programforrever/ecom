<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\ImageManagerStatic as Image;
use File;

class ConvertImagesToWebp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:convert-webp {--keep-original : Keep original images}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Convert all images to WebP format for better performance';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $keepOriginal = $this->option('keep-original');
        $uploadsPath = public_path('uploads');
        
        $this->info('🔄 Starting image conversion to WebP...');
        $this->info("📁 Uploads path: {$uploadsPath}");
        
        // Get all image files
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
        $files = File::allFiles($uploadsPath);
        
        $converted = 0;
        $skipped = 0;
        $errors = 0;
        $savedSpace = 0;
        
        $this->output->progressStart(count($files));
        
        foreach ($files as $file) {
            $this->output->progressAdvance();
            
            $extension = strtolower($file->getExtension());
            
            // Skip if not an image format
            if (!in_array($extension, $imageExtensions)) {
                $skipped++;
                continue;
            }
            
            // Skip if already WebP
            if ($extension === 'webp') {
                $skipped++;
                continue;
            }
            
            try {
                $filePath = $file->getPathname();
                $originalSize = filesize($filePath);
                
                // Generate WebP filename
                $webpPath = pathinfo($filePath, PATHINFO_DIRNAME) . '/' . 
                           pathinfo($filePath, PATHINFO_FILENAME) . '.webp';
                
                // Skip if WebP already exists
                if (file_exists($webpPath)) {
                    $skipped++;
                    continue;
                }
                
                // Convert to WebP
                $image = Image::make($filePath);
                $image->encode('webp', 85);
                $image->save($webpPath);
                
                $newSize = filesize($webpPath);
                $spaceSaved = $originalSize - $newSize;
                $savedSpace += $spaceSaved;
                
                // Delete original if not keeping
                if (!$keepOriginal) {
                    File::delete($filePath);
                }
                
                $converted++;
                
            } catch (\Exception $e) {
                $errors++;
                $this->error("Error converting {$file->getFilename()}: {$e->getMessage()}");
            }
        }
        
        $this->output->progressFinish();
        
        // Summary
        $this->info("\n✅ Conversion Complete!");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("📊 Statistics:");
        $this->line("   ✔️  Converted: <fg=green>{$converted}</>");
        $this->line("   ⏭️  Skipped: <fg=yellow>{$skipped}</>");
        $this->line("   ❌ Errors: <fg=red>{$errors}</>");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        
        $spaceSavedMB = round($savedSpace / (1024 * 1024), 2);
        $this->info("💾 Space Saved: <fg=green>{$spaceSavedMB} MB</>");
        $this->info("💡 Tip: Use --keep-original flag to preserve original images");
        
        return 0;
    }
}
