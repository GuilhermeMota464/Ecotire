<?php

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Configuração SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com'; 
    $mail->SMTPAuth   = true;
    $mail->Username   = 'ecotire.corporate@gmail.com';
    $mail->Password   = 'peiw yiyh vxlh qzyb';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // Remetente
    $mail->setFrom('ecotire.corporate@gmail.com', 'Ecotire Corporation');
    
    // Destinatário
    $mail->addAddress('guilherme.s.mota7@aluno.senai.br');

    // Conteúdo
    $mail->isHTML(true);
    $mail->Subject = 'Feedback do site - cliente: Guilherme';
    $mail->Body    = '<table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <!-- Estrutura principal com 600px -->
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="border: 1px solid #cccccc;">
                    <tr>
                        <td align="center" bgcolor="#ffffff" style="padding: 40px 0 30px 0;">
                            <h1 style="color: #333333;">Ecotire - Fale conosco</h1>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#ffffff" style="padding: 20px;">
                            <p style="color: #555555; line-height: 20px;">Achei tudo um lixo</p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" bgcolor="#007bff" style="padding: 15px; color: #ffffff;">
                            <a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ&list=RDdQw4w9WgXcQ&start_radio=1" style="color: #ffffff; text-decoration: none; font-weight: bold;">Clique Aqui</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>';

    $mail->send();
    echo 'E-mail enviado com sucesso!';
} catch (Exception $e) {
    echo "Erro ao enviar: {$mail->ErrorInfo}";
}
