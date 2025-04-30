<?php

namespace App\Helpers;

class MoodGreetingHelper
{
    public static function getGreeting(?string $mood, string $userName): string
    {
        $moodGreetings = [
            'sad' => "😢 Hai {$userName}, semoga kamu udah merasa lebih baik ya. Yuk kita ngobrol lagi bareng NUNO AI~",
            'happy' => "✨ Hai {$userName}, masih happy kan? Seru banget loh ngobrol sama kamu kemarin! 😄",
            'angry' => "😤 Hai {$userName}, semoga sekarang udah lebih chill ya. Gue di sini buat nemenin lo lagi~",
            'calm' => "🌿 Hai {$userName}, tenang banget ya hari ini. NUNO AI cocok banget buat nemenin chill lo.",
            'energetic' => "⚡ Yo {$userName}, semangat banget hari ini! Yuk kita bareng-bareng gasin harimu!",
            'romantic' => "💕 Uhuk {$userName}, lagi mellow-mellow mesra ya? Biar NUNO bantu tambahin vibes-nya.",
            'nostalgic' => "📼 Lagi kangen masa lalu ya, {$userName}? Yuk nostalgia bareng NUNO AI.",
            'lonely' => "😔 Hai {$userName}, kalau lagi sendiri, NUNO ada buat nemenin.",
            'anxious' => "🫨 Lagi gelisah? Santai, {$userName}, gue bantu tenangin pelan-pelan.",
            'confident' => "💪 Hai {$userName}, percaya diri banget nih! Sikat terus bareng NUNO!",
            'moody' => "🌫️ Mood naik turun ya? Yuk curhat aja, NUNO dengerin kok.",
            'hopeful' => "🌈 Semangat terus ya, {$userName}! Masa depan lo terang banget.",
            'grateful' => "🙏 Wah, bersyukur tuh mood yang keren banget, {$userName}.",
            'frustrated' => "😣 Lagi pengen marah? Gue dengerin lo, tanpa nge-judge.",
            'joyful' => "🥳 Seru banget hari ini ya, {$userName}! Lanjut gasin!",
            'melancholy' => "🌧️ Lagi sendu-sendunya nih... Biar NUNO kasih pelukan virtual.",
            'tired' => "😴 Capek ya? Yuk istirahat bareng, gue ada di sini kok.",
            'broken' => "💔 Waduh, patah hati ya? NUNO siap dengerin semua keluh kesah lo.",
            'rebellious' => "🔥 Lagi pengen lawan dunia? Bareng NUNO boleh banget!",
            'motivated' => "🚀 Yuk wujudkan semua rencana lo, {$userName}!",
            'playful' => "😜 Lagi pengen ketawa terus ya? Asik, cocok sama NUNO nih!",
            'serene' => "🕊️ Damai banget... Biar NUNO bantu jaga zen lo.",
            'lost' => "🧭 Lagi nyari arah? Gak apa-apa, kita bareng-bareng cari jawabannya.",
            'dramatic' => "🎭 Waduh, kayaknya hidup lagi sinetron banget ya?",
            'curious' => "🧐 Yuk kita telusuri hal-hal baru bareng!",
            'focused' => "🎯 Lagi fokus banget nih! Gue bantu lo tetap di jalur.",
            'inspired' => "✨ Wah keren, lagi dapet ide ya? Ceritain dong ke NUNO.",
            'excited' => "🤩 Wih, ada yang semangat maksimal hari ini!",
            'peaceful' => "🌼 Hening dan adem banget ya hari ini. Suka deh!",
            'yearning' => "🌙 Rindu itu rumit ya, tapi bisa lo ceritain ke gue.",
            'trapped' => "🕳️ Ngerasa stuck? Yuk gue bantu pelan-pelan keluar.",
            'bitter' => "🧂 Rasanya pahit ya... Tapi lo gak sendirian kok.",
            'relaxed' => "🛋️ Lagi santai banget? Nikmatin aja bareng gue.",
            'upbeat' => "🎶 Energi lo tinggi banget! Yuk kita manfaatin maksimal!",
        ];

        return $moodGreetings[$mood] ?? null;
    }
}
