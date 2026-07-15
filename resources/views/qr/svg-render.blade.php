@php
    $eyeColor = $qrCode->eye_color ?? $qrCode->foreground_color ?? '#000000';

    $options = new \Chillerlan\QRCode\QROptions([
        'outputInterface' => \Chillerlan\QRCode\Output\QRMarkupSVG::class,
        'eccLevel' => \Chillerlan\QRCode\QRCode::ECC_M,
        'scale' => max(1, intval($qrCode->size / 50)),
        'bgColor' => $qrCode->background_color ?? '#FFFFFF',
        'drawLightModules' => true,
        'moduleValues' => [
            \Chillerlan\QRCode\Data\QRMatrix::M_DATA_DARK => $qrCode->foreground_color ?? '#000000',
            \Chillerlan\QRCode\Data\QRMatrix::M_FINDER_DARK => $eyeColor,
            \Chillerlan\QRCode\Data\QRMatrix::M_FINDER_DOT => $eyeColor,
            \Chillerlan\QRCode\Data\QRMatrix::M_ALIGNMENT_DARK => $qrCode->foreground_color ?? '#000000',
        ],
        'quietzoneSize' => $qrCode->margin ?? 10,
    ]);

    $qr = new \Chillerlan\QRCode\QRCode($options);
    echo $qr->render($qrCode->content);
@endphp
