<?php
function generateInviteCode($pdo, $user_id) {
    $stmt = $pdo->prepare("
        SELECT invite_code FROM invites 
        WHERE user_id = ? AND expires_at > datetime('now')
    ");
    $stmt->execute([$user_id]);
    $existing = $stmt->fetch();
    if ($existing) return $existing['invite_code'];
    $code = bin2hex(random_bytes(16));
    $stmt = $pdo->prepare("
        INSERT INTO invites (user_id, invite_code, expires_at) 
        VALUES (?, ?, datetime('now', '+7 days'))
    ");
    $stmt->execute([$user_id, $code]);
    return $code;
}
?>