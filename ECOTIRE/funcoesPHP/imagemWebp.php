<?php
/**
 * Converte o conteúdo binário de uma imagem (MEDIUMBLOB) para WebP
 * e devolve um data URI pronto para usar em <img src="...">.
 * Se o GD não tiver suporte a WebP, devolve a imagem original.
 */
function imagemParaDataUri($blob, $qualidade = 85)
{
    if (empty($blob)) {
        return null;
    }

    if (function_exists('imagecreatefromstring') && function_exists('imagewebp')) {
        $img = @imagecreatefromstring($blob);

        if ($img !== false) {
            // Necessário para PNG indexado (paleta) e para manter transparência
            if (!imageistruecolor($img)) {
                imagepalettetotruecolor($img);
            }
            imagealphablending($img, true);
            imagesavealpha($img, true);

            ob_start();
            $ok = imagewebp($img, null, $qualidade);
            $webp = ob_get_clean();
            imagedestroy($img);

            if ($ok && $webp !== '') {
                return 'data:image/webp;base64,' . base64_encode($webp);
            }
        }
    }

    // Fallback: imagem original
    $mime = (new finfo(FILEINFO_MIME_TYPE))->buffer($blob) ?: 'application/octet-stream';
    return 'data:' . $mime . ';base64,' . base64_encode($blob);
}