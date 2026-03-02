@extends('backend.layouts.app')

@section('content')
<div class="page-content">
    <div class="aiz-titlebar text-left mt-2 pb-2 px-3 px-md-2rem border-bottom border-gray">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3">{{ translate('Image Optimization') }}</h1>
                <p class="mb-0 text-secondary">{{ translate('Convert all product images to WebP format for better performance') }}</p>
            </div>
        </div>
    </div>

    <div class="page-content bg-white">
        <div class="container-fluid">
            <div class="row mt-4">
                <div class="col-md-8 mx-auto">
                    <!-- Info Cards -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <i class="fas fa-image fa-2x text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted mb-1">{{ translate('Total Images') }}</h6>
                                            <h3 class="mb-0">{{ $imageCount }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <i class="fas fa-check-circle fa-2x text-success"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted mb-1">{{ translate('WebP Already Converted') }}</h6>
                                            <h3 class="mb-0">{{ $webpCount }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Card -->
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-light border-bottom">
                            <h5 class="mb-0">{{ translate('WebP Conversion Settings') }}</h5>
                        </div>
                        <div class="card-body">
                            <!-- Info Alert -->
                            <div class="alert alert-info border-0" role="alert">
                                <i class="fas fa-lightbulb mr-2"></i>
                                <strong>{{ translate('Benefits of WebP:') }}</strong>
                                <ul class="mb-0 mt-2">
                                    <li>{{ translate('25-35% smaller file sizes than JPEG/PNG') }}</li>
                                    <li>{{ translate('Better page loading performance') }}</li>
                                    <li>{{ translate('Latest web standard supported by all modern browsers') }}</li>
                                    <li>{{ translate('No loss in image quality') }}</li>
                                </ul>
                            </div>

                            <!-- Settings Form -->
                            <form id="conversionForm">
                                @csrf
                                
                                <!-- Keep Originals Option -->
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="keepOriginal" name="keep_original" value="1">
                                        <label class="custom-control-label" for="keepOriginal">
                                            {{ translate('Keep original images') }}
                                        </label>
                                    </div>
                                    <small class="form-text text-muted d-block mt-1">
                                        ⚠️ {{ translate('Note: Uncheck to save disk space. Original images will be deleted.') }}
                                    </small>
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-4">
                                    <button type="button" class="btn btn-primary btn-lg btn-block" id="convertButton">
                                        <i class="fas fa-sync-alt mr-2"></i>
                                        {{ translate('Start Conversion') }}
                                    </button>
                                </div>
                            </form>

                            <!-- Progress Section (Hidden initially) -->
                            <div id="progressSection" style="display: none;" class="mt-4">
                                <div class="alert alert-warning" role="alert">
                                    <i class="fas fa-hourglass-half mr-2"></i>
                                    <strong>{{ translate('Conversion in progress...') }}</strong>
                                    <p class="mb-0 mt-2">{{ translate('Do not close this page or refresh the browser.') }}</p>
                                </div>

                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" id="progressBar" 
                                         role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                        <span id="progressText">0%</span>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <p id="statusMessage" class="text-muted">{{ translate('Starting conversion...') }}</p>
                                </div>
                            </div>

                            <!-- Results Section (Hidden initially) -->
                            <div id="resultsSection" style="display: none;" class="mt-4">
                                <div id="successAlert" class="alert alert-success border-0" style="display: none;">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    <strong>{{ translate('Conversion Completed Successfully!') }}</strong>
                                </div>

                                <div id="errorAlert" class="alert alert-danger border-0" style="display: none;">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <strong id="errorMessage"></strong>
                                </div>

                                <table class="table table-bordered">
                                    <tr>
                                        <td>{{ translate('Images Converted') }}</td>
                                        <td class="font-weight-bold text-success"><span id="resultConverted">0</span> ✔️</td>
                                    </tr>
                                    <tr>
                                        <td>{{ translate('Images Skipped') }}</td>
                                        <td class="font-weight-bold"><span id="resultSkipped">0</span> ⏭️</td>
                                    </tr>
                                    <tr>
                                        <td>{{ translate('Errors') }}</td>
                                        <td class="font-weight-bold text-danger"><span id="resultErrors">0</span> ❌</td>
                                    </tr>
                                    <tr class="bg-light">
                                        <td><strong>{{ translate('Space Saved') }}</strong></td>
                                        <td class="font-weight-bold text-primary"><strong><span id="resultSpace">0</span> MB</strong></td>
                                    </tr>
                                </table>

                                <button type="button" class="btn btn-primary btn-lg btn-block mt-3" id="restartButton">
                                    <i class="fas fa-redo mr-2"></i>
                                    {{ translate('Convert Again') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="alert alert-info mt-3 border-0" role="alert">
                        <h6 class="mb-2"><i class="fas fa-terminal mr-2"></i>{{ translate('Alternative: Use Command Line') }}</h6>
                        <p class="mb-0">
                            {{ translate('You can also run the conversion using the command:') }}<br>
                            <code class="bg-light p-2 d-block mt-1 rounded">php artisan images:convert-webp --keep-original</code>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('convertButton').addEventListener('click', async function() {
    if ({{ $imageCount }} === 0) {
        alert('{{ translate("No images found to convert!") }}');
        return;
    }

    if (!confirm('{{ translate("Start converting all images to WebP format?") }}')) {
        return;
    }

    const button = this;
    button.disabled = true;
    
    document.getElementById('progressSection').style.display = 'block';
    document.getElementById('resultsSection').style.display = 'none';

    try {
        const response = await fetch('{{ route("image-optimization.convert") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({
                keep_original: document.getElementById('keepOriginal').checked ? 1 : 0
            })
        });

        const data = await response.json();

        if (data.success) {
            // Show results
            document.getElementById('progressSection').style.display = 'none';
            document.getElementById('resultsSection').style.display = 'block';
            document.getElementById('successAlert').style.display = 'block';
            document.getElementById('errorAlert').style.display = 'none';

            document.getElementById('resultConverted').textContent = data.converted;
            document.getElementById('resultSkipped').textContent = data.skipped;
            document.getElementById('resultErrors').textContent = data.errors;
            document.getElementById('resultSpace').textContent = data.space_saved_mb;
        } else {
            throw new Error(data.error || 'Conversion failed');
        }
    } catch (error) {
        document.getElementById('progressSection').style.display = 'none';
        document.getElementById('resultsSection').style.display = 'block';
        document.getElementById('successAlert').style.display = 'none';
        document.getElementById('errorAlert').style.display = 'block';
        document.getElementById('errorMessage').textContent = error.message;
    } finally {
        button.disabled = false;
    }
});

document.getElementById('restartButton').addEventListener('click', function() {
    location.reload();
});
</script>
@endsection
