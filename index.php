<?php

#================================================

define('API_KEY', 'your token');

$idbot = //bot id;
$userbot = 'vicalinauzbot';
$umniycoder = //admin id;
$owners = array($umniycoder);
$user = "admin user";

define('DB_HOST', 'localhost');
define('DB_USER', 'db usernamegit ');
define('DB_PASS', 'db pass');
define('DB_NAME', 'db name');

$connect = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
mysqli_set_charset($connect, 'utf8mb4');

function bot($method, $datas = [])
{
    $url = "https://api.telegram.org/bot" . API_KEY . "/" . $method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
    $res = curl_exec($ch);
    if (curl_error($ch)) var_dump(curl_error($ch));
    else return json_decode($res);
}

#================================================

function sendMessage($id, $text, $key = null)
{
    return bot('sendMessage', [
        'chat_id' => $id,
        'text' => $text,
        'parse_mode' => 'html',
        'disable_web_page_preview' => true,
        'reply_markup' => $key
    ]);
}

function editMessageText($cid, $mid, $text, $key = null)
{
    return bot('editMessageText', [
        'chat_id' => $cid,
        'message_id' => $mid,
        'text' => $text,
        'parse_mode' => 'html',
        'disable_web_page_preview' => true,
        'reply_markup' => $key
    ]);
}

function sendVideo($cid, $f_id, $text, $key = null)
{
    return bot('sendVideo', [
        'chat_id' => $cid,
        'video' => $f_id,
        'caption' => $text,
        'parse_mode' => 'html',
        'reply_markup' => $key
    ]);
}
function sendPhoto($cid, $photo, $text, $key = null)
{
    return bot('sendPhoto', [
        'chat_id' => $cid,
        'photo' => $photo,
        'caption' => $text,
        'parse_mode' => 'html',
        'reply_markup' => $key
    ]);
}


function copyMessage($id, $from_chat_id, $message_id)
{
    return bot('copyMessage', [
        'chat_id' => $id,
        'from_chat_id' => $from_chat_id,
        'message_id' => $message_id
    ]);
}

function forwardMessage($id, $cid, $mid)
{
    return bot('forwardMessage', [
        'from_chat_id' => $id,
        'chat_id' => $cid,
        'message_id' => $mid
    ]);
}

function deleteMessage($cid, $mid)
{
    return bot('deleteMessage', [
        'chat_id' => $cid,
        'message_id' => $mid
    ]);
}

function getChatMember($cid, $userid)
{
    return bot('getChatMember', [
        'chat_id' => $cid,
        'user_id' => $userid
    ]);
}

function replyKeyboard($key)
{
    return json_encode(['keyboard' => $key, 'resize_keyboard' => true]);
}

function getName($id)
{
    $getname = bot('getchat', ['chat_id' => $id])->result->first_name;
    if (!empty($getname)) {
        return $getname;
    } else {
        return bot('getchat', ['chat_id' => $id])->result->title;
    }
}
mkdir("admin");
function joinchat($id)
{
    $array = array("inline_keyboard");
    $kanallar = file_get_contents("admin/kanal.txt");
    if ($kanallar == null) {
        return true;
    } else {
        $ex = explode(" ", $kanallar);
        for ($i = 0; $i <= count($ex) - 1; $i++) {
            $first_line = $ex[$i];
            $first_ex = explode("@", $first_line);
            $url = $first_ex[1];
            $ism = bot('getChat', ['chat_id' => "@" . $url])->result->title;
            $ret = bot("getChatMember", [
                "chat_id" => "@$url",
                "user_id" => $id,
            ]);
            $stat = $ret->result->status;
            if ($url == null) {
                $stat = "member";
            }
            if ((($stat == "creator" or $stat == "administrator" or $stat == "member"))) {
                $array['inline_keyboard']["$i"][0]['text'] = "✅ " . $ism;
                $array['inline_keyboard']["$i"][0]['url'] = "https://t.me/$url";
            } else {
                $array['inline_keyboard']["$i"][0]['text'] = "❌ " . $ism;
                $array['inline_keyboard']["$i"][0]['url'] = "https://t.me/$url";
                $uns = true;
            }
        }
        $array['inline_keyboard']["$i"][0]['text'] = "🔄 Tekshirish";
        $array['inline_keyboard']["$i"][0]['callback_data'] = "check";
        if ($uns == true) {
            bot('sendMessage', [
                'chat_id' => $id,
                'text' => "<b>⚠️ Botdan to'liq foydalanish uchun quyidagi kanallarimizga obuna bo'ling!</b>",
                'parse_mode' => 'html',
                'disable_web_page_preview' => true,
                'reply_markup' => json_encode($array),
            ]);
            return false;
        } else {
            return true;
        }
    }
}

#================================================

date_Default_timezone_set('Asia/Tashkent');
$soat = date('H:i');
$sana = date("d.m.Y");

#================================================

$update = json_decode(file_get_contents('php://input'));

$message = $update->message;
$callback = $update->callback_query;
$query = $update->inline_query->query;

if (isset($message)) {
    $cid = $message->chat->id;
    $Tc = $message->chat->type;

    $text = $message->text;
    $mid = $message->message_id;

    $from_id = $message->from->id;
    $name = $message->from->first_name;
    $last = $message->from->last_name;

    $photo = $message->photo[count($message->photo) - 1]->file_id;

    $video = $message->video;
    $file_id = $video->file_id;
    $file_name = $video->file_name;
    $file_size = $video->file_size;
    $size = $file_size / 1000;
    $dtype = $video->mime_type;

    $audio = $message->audio->file_id;
    $voice = $message->voice->file_id;
    $sticker = $message->sticker->file_id;
    $video_note = $message->video_note->file_id;
    $animation = $message->animation->file_id;

    $caption = $message->caption;
}

if (isset($callback)) {
    $data = $callback->data;
    $qid = $callback->id;

    $cid = $callback->message->chat->id;
    $Tc = $callback->message->chat->type;
    $mid = $callback->message->message_id;

    $from_id = $callback->from->id;
    $name = $callback->from->first_name;
    $last = $callback->from->last_name;
}

#=================================================

$kino_id = file_get_contents("admin/kino.txt");
$kino = bot('getchat', ['chat_id' => $kino_id])->result->username;
$reklama = str_replace(["%kino%", "%admin%"], [$kino, $user], file_get_contents("admin/rek.txt"));


#================================================

$admins = explode("\n", file_get_contents("admin/admins.txt"));
if (is_array($admins)) $admin = array_merge($owners, $admins);
else $admin = $owners;

#=================================================

$user = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `user_id` WHERE `id` = $cid"));
$iduser = $user['id'];
$step = $user['step'];
$ban = $user['ban'];
$lastmsg = $user['lastmsg'];
$isadmin = $user['admin'];
$isreg = $user['is_reg'];

$userdata = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `user_id` WHERE `id` = $cid"));
$userid = $userdata['user_id'];
$nameuser = $userdata['name'];
$surnameuser = $userdata['surname'];
$birthdayuser = $userdata['birthday'];


$adminlist = mysqli_query($connect, "SELECT id FROM `user_id` WHERE `admin` = 1");

$admin_ids = [];
while ($row = mysqli_fetch_assoc($adminlist)) {
    $admin_ids[] = $row['id'];
}

$adminid = $admin_ids;

#=================================================

if ($ban == 1) exit();

if (isset($message)) {
    if (!$connect) {
        sendMessage($cid, "⚠️ <b>Ma'lumotlar olishda xatolik!</b>\n\n<i>Iltimos tezroq adminga xabar bering.</i>");
        return false;
    }
}

if ($Tc == "private") {
    $result = mysqli_query($connect, "SELECT * FROM `user_id` WHERE `id` = $cid");
    $rew = mysqli_fetch_assoc($result);
    if ($rew) {
    } else {
        mysqli_query($connect, "INSERT INTO `user_id`(`id`,`step`,`sana`,`ban`) VALUES ('$cid','0','$sana | $soat','0')");
    }
}


#=================================================

$panel = replyKeyboard([
    [['text' => "👤 Userlar"], ['text' => "🗂️ Ma'lumotlar"]],
    [['text' => "🚪 Paneldan chiqish"]]
]);

$cancel = replyKeyboard([
    [['text' => "◀️ Orqaga"]]
]);

$userlar_p = replyKeyboard([
    [['text' => "🔴 Blocklash"], ['text' => "🟢 Blockdan olish"]],
    [['text' => "✍️ Post xabar"], ['text' => "📬 Forward xabar"]],
    [['text' => "📈 Statistika"]],
    [['text' => "◀️ Orqaga"]]
]);

$malumotlar_p = replyKeyboard([
    [['text' => "➕ Mahsulot qo'shish"], ['text' => "🗑️ Mahsulot o'chirish"]],
    [['text' => "◀️ Orqaga"]]
]);


$removeKey = json_encode(['remove_keyboard' => true]);

#=================================================


if (isset($message)) {

    // Foydalanuvchi bazada bormi?
    $userQuery = mysqli_query($connect, "SELECT * FROM `data` WHERE `user_id` = '$cid'");
    $user = mysqli_fetch_assoc($userQuery);

    // Agar foydalanuvchi `/start` bosgan bo‘lsa yoki bazada bo‘lmasa
    if ($text == "/start") {
        if (!$user) {
            mysqli_query($connect, "INSERT INTO `data` (`user_id`, `name`) VALUES ('$cid', 'waiting')");
            sendMessage($cid, "Ismingizni kiriting:");
        } else {
            // Qaysi maydon bo‘shligini tekshirib, o‘shani so‘raymiz
            if (!$user['name'] || $user['name'] == "waiting") {
                sendMessage($cid, "👇Ismingizni kiriting:");
                mysqli_query($connect, "UPDATE `data` SET `name` = 'waiting' WHERE `user_id` = '$cid'");
            } elseif (!$user['surname'] || $user['surname'] == "waiting") {
                sendMessage($cid, "👇Familyangizni kiriting:");
                mysqli_query($connect, "UPDATE `data` SET `surname` = 'waiting' WHERE `user_id` = '$cid'");
            } elseif (!$user['birthday'] || $user['birthday'] == "waiting") {
                sendMessage($cid, "👇Tug‘ilgan sanangizni kiriting (YYYY-MM-DD):");
                mysqli_query($connect, "UPDATE `data` SET `birthday` = 'waiting' WHERE `user_id` = '$cid'");
            } else {
                sendMessage($cid, "🖐Assalomu alaykum \n\n✍Kerakli kodni yuboring:", $removeKey);
                mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'start' WHERE `id` = $cid");
            }
        }
        exit();
    }

    // Foydalanuvchi hali ro‘yxatdan o‘tmagan bo‘lsa, ma’lumotlarni to‘ldirishni majburlash
    if ($user) {
        if ($user['name'] == "waiting") {
            mysqli_query($connect, "UPDATE `data` SET `name` = '$text', `surname` = 'waiting' WHERE `user_id` = '$cid'");
            sendMessage($cid, "👇Familyangizni kiriting:");
            exit();
        }
        if ($user['surname'] == "waiting") {
            mysqli_query($connect, "UPDATE `data` SET `surname` = '$text', `birthday` = 'waiting' WHERE `user_id` = '$cid'");
            sendMessage($cid, "👇Tug‘ilgan sanangizni kiriting (YYYY-MM-DD):");
            exit();
        }
        if ($user['birthday'] == "waiting") {
            if (preg_match("/^\d{4}-\d{2}-\d{2}$/", $text)) {
                mysqli_query($connect, "UPDATE `data` SET `birthday` = '$text' WHERE `user_id` = '$cid'");
                sendMessage($cid, "🖐Assalomu alaykum \n\n✍Kerakli kodni yuboring:", $removeKey);
                mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'start' WHERE `id` = $cid");
            } else {
                sendMessage($cid, "Iltimos, sanani YYYY-MM-DD formatida kiriting:");
            }
            exit();
        }
    } else {
        sendMessage($cid, "Iltimos, avval /start buyrug‘ini bering.");
        exit();
    }

    // 🔥 **SHU YERGA BOSHQA BUYRUQLARINGIZNI QO‘SHING** 🔥



    #=================================================

    if (($text == "/panel" or $text == "/a" or $text == "/admin" or $text == "/p" or $text == "◀️ Orqaga") and $isadmin) {
        sendMessage($cid, "<b>👨🏻‍💻 Boshqaruv paneliga xush kelibsiz.</b>\n\n<i>Nimani o'zgartiramiz?</i>", $panel);
        mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'panel' WHERE `id` = $cid");
        mysqli_query($connect, "UPDATE `user_id` SET `step` = '0' WHERE `id` = $cid");
        exit();
    } else if ($text == "🚪 Paneldan chiqish" and $isadmin) {
        sendMessage($cid, "<b>🚪 Panelni tark etdingiz unga /panel yoki /admin xabarini yuborib kirishingiz mumkin.\n\nYangilash /start</b>", $removeKey);
        mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'start' WHERE `id` = $cid");
        mysqli_query($connect, "UPDATE `user_id` SET `step` = '0' WHERE `id` = $cid");
        exit();
    } else if ($text == "🗂️ Ma'lumotlar" and $isadmin) {
        sendMessage($cid, "<b>🗂️ Ma'lumotlar bo'limi.\n🆔 Admin: $cid</b>", $malumotlar_p);
        mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'manuals' WHERE `id` = $cid");
        exit();
    } else if ($text == "➕ Mahsulot qo'shish" and $isadmin) {
        sendMessage($cid, "<b>💡 Mahsulot qo'shish uchun menga uning rasmini yuboring:</b>", $cancel);
        mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'addProduct' WHERE `id` = $cid");
        mysqli_query($connect, "UPDATE `user_id` SET `step` = 'product-add' WHERE `id` = $cid");
        exit();
    }
    if (isset($photo) && $step === "product-add" && $isadmin) {
        $result = mysqli_query($connect, "SELECT * FROM products WHERE img = '$file_name'");
        $row = mysqli_fetch_assoc($result);
        if (!$row) {
            $res = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM products ORDER BY id DESC LIMIT 1"));
            $rand = $res['code'] + 1;
            $file_name = str_replace("_", " ", $file_name);
            $sql = "INSERT INTO `products`(`img`,`code`, `create_at`) VALUES ('$photo','$rand', '$sana')";
            $res = mysqli_query($connect, $sql);

            if ($res) {
                sendMessage($cid, "<b>✅ Bazaga muvaffaqiyatli joylandi!\n\n<i>Iltimos mahsulot nomini yuboring:</i></b>", $cancel);

                $update1 = mysqli_query($connect, "UPDATE user_id SET lastmsg = '$rand' WHERE id = $cid");
                $update2 = mysqli_query($connect, "UPDATE user_id SET step = 'setname' WHERE id = $cid");

                if (!$update1 || !$update2) {
                    sendMessage($cid, "⚠️ Xatolik yuz berdi: " . mysqli_error($connect), $cancel);
                }
            } else {
                sendMessage($cid, "❌ Xatolik: " . mysqli_error($connect), $cancel);
            }

            exit();
        } else {
            sendMessage($cid, "$file_name <b>qabul qilinmadi!</b>\n\nQayta urinib ko'ring:");
            exit();
        }
    } else if ($step == "setname" and $isadmin) {
        $result = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM user_id WHERE id = '$cid'"));
        $code = $result['lastmsg'];
        sendMessage($cid, "<b>✅ Mahsulot nomi saqlandi!\n\nIltimos narxini yuboring</b>", $cancel);
        mysqli_query($connect, "UPDATE user_id SET step = 'setprice' WHERE id = $cid");
        mysqli_query($connect, "UPDATE `products` SET `name` = '$text' WHERE `code` = $code");
    } else if ($step == "setprice" and $isadmin) {
        $result = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM user_id WHERE id = '$cid'"));
        $code = $result['lastmsg'];
        sendMessage($cid, "<b>✅ Mahsulot narxi saqlandi! \n\nIltimos mahsulot haqida ma'lumot yuboring</b>", $cancel);
        mysqli_query($connect, "UPDATE user_id SET step = 'setinfo' WHERE id = $cid");
        mysqli_query($connect, "UPDATE `products` SET `price` = '$text' WHERE `code` = $code");
    }else if ($step == "setinfo" and $isadmin) {
        $result = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM user_id WHERE id = '$cid'"));
        $code = $result['lastmsg'];
        $texts = mysqli_real_escape_string($connect, $text);
        sendMessage($cid, "<b>✅ Mahsulot ma'lumoti saqlandi! \n\nIltimos mahsulot kodini yuboring</b>", $cancel);
        mysqli_query($connect, "UPDATE user_id SET step = 'setcode' WHERE id = $cid");
        mysqli_query($connect, "UPDATE `products` SET `info` = '$texts' WHERE `code` = $code");
    }else if ($step == "setcode" and $isadmin) {
        $result = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM user_id WHERE id = '$cid'"));
        $code = $result['lastmsg'];
        sendMessage($cid, "<b>✅ Mahsulot to'liq holda yuklandi!</b>", $malumotlar_p);
        mysqli_query($connect, "UPDATE user_id SET step = 'start' WHERE id = $cid");
        mysqli_query($connect, "UPDATE user_id SET lastmsg = 'panel' WHERE id = $cid");
        mysqli_query($connect, "UPDATE `products` SET `code` = '$text' WHERE `code` = $code");
        exit();
    } else if ($text == "🗑️ Mahsulot o'chirish" and $isadmin) {
        sendMessage($cid, "<b>🗑️ Mahsulot o'chirish uchun menga kino kodini yuboring:</b>", $cancel);
        mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'productdelete' WHERE `id` = $cid");
        mysqli_query($connect, "UPDATE `user_id` SET `step` = 'product-remove' WHERE `id` = $cid");
        exit();
    } else if (($step == "product-remove" and $text != "🗑️ Mahsulot o'chirish") and $isadmin) {
        $res = mysqli_query($connect, "SELECT * FROM `products` WHERE `code` = '$text'");
        $row = mysqli_fetch_assoc($res);
        if ($row) {
            mysqli_query($connect, "DELETE FROM `products` WHERE `code` = $text");
            sendMessage($cid, "🗑️ $text <b>raqamli mahsulot olib tashlandi!</b>", $malumotlar_p);
            mysqli_query($connect, "UPDATE `user_id` SET `step` = '0' WHERE `id` = $cid");
            mysqli_query($connect, "UPDATE user_id SET `lastmsg` = 'start' WHERE `id` = $cid");
            exit();
        } else {
            sendMessage($cid, "📛 $text <b>mavjud emas!</b>\n\n🔄 Qayta urinib ko'ring:");
            exit();
        }
    } else if ($text == "💡 Mahsulot kanal" and $isadmin) {
        sendMessage($cid, "<b>💡 Mahsulot kanal havolasini yuboring!\n\nNa'muna: @umniycoder</b>", $cancel);
        mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'product_chan' WHERE `id` = $cid");
        mysqli_query($connect, "UPDATE `user_id` SET `step` = 'product_chan' WHERE `id` = $cid");
        exit();
    } else if (($step == "product_chan" and $text != "💡 Mahsulot kanal") and $isadmin) {
        $nn_id = bot('getchat', ['chat_id' => $text])->result->id;
        sendMessage($cid, "<b>✅ $text ga o'zgartirildi.</b>", $panel);
        file_put_contents("admin/kino.txt", $nn_id);
        mysqli_query($connect, "UPDATE `user_id` SET `step` = '0' WHERE `id` = $cid");
    } else if ($text == "👤 Userlar" and $isadmin) {
        sendMessage($cid, "<b>👥Userlar boshqaruvi bo'limi:\n🆔 Admin: $cid</b>", $userlar_p);
        mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'users' WHERE `id` = $cid");
        exit();
    } else if ($text == "🔴 Blocklash" and $isadmin) {
        sendMessage($cid, "<b>Foydalanuvchi ID raqamini kiriting:</b>\n\n<i>M-n: $cid</i>", $cancel);
        mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'addblock' WHERE `id` = $cid");
        mysqli_query($connect, "UPDATE `user_id` SET `step` = 'blocklash' WHERE `id` = $cid");
        exit();
    } else if (($step == "blocklash" and $text != "🔔 Blocklash") and $isadmin) {
        sendMessage($cid, "<b>✅ $text blocklandi!</b>", $panel);
        mysqli_query($connect, "UPDATE `user_id` SET `ban` = 1 WHERE `id` = $text");
        mysqli_query($connect, "UPDATE `user_id` SET `step` = '0' WHERE `id` = $cid");
        exit();
    } else if ($text == "🟢 Blockdan olish" and $isadmin) {
        sendMessage($cid, "<b>Foydalanuvchi ID raqamini kiriting:</b>\n\n<i>M-n: $cid</i>", $cancel);
        mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'deleteBlock' WHERE 	 = $cid");
        mysqli_query($connect, "UPDATE `user_id` SET `step` = 'blockdanolish' WHERE `id` = $cid");
        exit();
    } else if (($step == "blockdanolish" and $text != "🔕 Blockdan olish") and $isadmin) {
        sendMessage($cid, "<b>✅ $text blockdan olindi!</b>", $panel);
        mysqli_query($connect, "UPDATE `user_id` SET `ban` = 0 WHERE `id` = $text");
        mysqli_query($connect, "UPDATE `user_id` SET `step` = '0' WHERE `id` = $cid");
        exit();
    } else if ($text == "✍️ Post xabar" and $isadmin) {
        sendMessage($cid, "<b>Xabaringizni yuboring:</b>");
        mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'post_msg' WHERE `id` = $cid");
        mysqli_query($connect, "UPDATE `user_id` SET `step` = 'post_send' WHERE `id` = $cid");
        exit();
    } else if (($step == "post_send" and $text != "✍️ Post xabar") and $isadmin) {
        mysqli_query($connect, "UPDATE `user_id` SET `step` = '0' WHERE `id` = $cid");
        $msg = sendMessage($cid, "✅ <b>Xabar yuborish boshlandi!</b>", $panel)->result->message_id;
        $yuborildi = 0;
        $yuborilmadi = 0;
        $result = mysqli_query($connect, "SELECT * FROM `user_id`");
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $ok = copyMessage($id, $cid, $mid)->ok;
            if ($ok == true) $yuborildi++;
            else $yuborilmadi++;
            editMessageText($cid, $msg, "✅ <b>Yuborildi:</b> {$yuborildi}taga\n❌ <b>Yuborilmadi:</b> {$yuborilmadi}taga");
        }
        deleteMessage($cid, $msg);
        sendMessage($cid, "💡 <b>Xabar yuborish tugatildi.\n\n</b>✅ <b>Yuborildi:</b> {$yuborildi}taga\n❌ <b>Yuborilmadi:</b> {$yuborilmadi}taga\n\n<b>⏰ Soat: $soat | 📆 Sana: $sana</b>", $panel);
    } else if ($text == "📬 Forward xabar" and $isadmin) {
        sendMessage($cid, "<b>Xabaringizni yuboring:</b>");
        mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'post_msg' WHERE `id` = $cid");
        mysqli_query($connect, "UPDATE `user_id` SET `step` = 'forward_send' WHERE `id` = $cid");
        exit();
    } else if (($step == "forward_send" and $text != "📬 Forward xabar") and $isadmin) {
        mysqli_query($connect, "UPDATE `user_id` SET `step` = '0' WHERE `id` = $cid");
        $msg = sendMessage($cid, "✅ <b>Xabar yuborish boshlandi!</b>", $panel)->result->message_id;
        $result = mysqli_query($connect, "SELECT * FROM `user_id`");
        $yuborildi = 0;
        $yuborilmadi = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $ok = forwardMessage($cid, $id, $mid)->ok;
            if ($ok == true) $yuborildi++;
            else $yuborilmadi++;
            editMessageText($cid, $msg, "✅ <b>Yuborildi:</b> {$yuborildi}taga\n❌ <b>Yuborilmadi:</b> {$yuborilmadi}taga");
        }
        deleteMessage($cid, $msg);
        sendMessage($cid, "💡 <b>Xabar yuborish tugatildi.\n\n</b>✅ <b>Yuborildi:</b> {$yuborildi}taga\n❌ <b>Yuborilmadi:</b> {$yuborilmadi}taga\n\n<b>⏰ Soat: $soat | 📆 Sana: $sana</b>", $panel);
    } else if ($text == "📈 Statistika" or $text == "/stat") {
        $res = mysqli_query($connect, "SELECT * FROM `user_id`");
        $us = mysqli_num_rows($res);
        $res = mysqli_query($connect, "SELECT * FROM `products`");
        $kin = mysqli_num_rows($res);
        $ping = sys_getloadavg()[2];
        $mmtxt = "📊┌ STATISTIKA
👥├ A`zolar: $us
🎦├ Jami mahsulotlar: $kin
⏳├ Hozirgi vaqt $soat
📆└ Bugun $sana";
        sendMessage($cid, "$mmtxt");
        mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'stat' WHERE `id` = $cid");
        exit();
    } else if ($data == "stat" or $data == "refresh") {
        $back = json_encode(['inline_keyboard' => [
            [['text' => "♻️ Yangilash", 'callback_data' => "refresh"]],
            [['text' => "🔙 Orqaga", 'callback_data' => "back"]]
        ]]);
        $res = mysqli_query($connect, "SELECT * FROM `user_id`");
        $us = mysqli_num_rows($res);
        $res = mysqli_query($connect, "SELECT * FROM `products`");
        $kin = mysqli_num_rows($res);
        $ping = sys_getloadavg()[2];
        $mmtxt = "📊┌ STATISTIKA
👥├ A`zolar: $us
🎦├ Jami mahsulotlar: $kin
⏳├ Hozirgi vaqt $soat
📆└ Bugun $sana";
        editMessageText($cid, $mid, "$mmtxt", $back);
        mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'stat' WHERE `id` = $cid");
        exit();
    } else if (($text == "👨‍💼 Adminlar" or $data == "admins") and $isadmin) {
        if (isset($data)) deleteMessage($cid, $mid);
        $keyBot = json_encode(['inline_keyboard' => [
            [['text' => "➕ Yangi admin qo'shish", 'callback_data' => "add-admin"]],
            [['text' => "📑 Ro'yxat", 'callback_data' => "list-admin"], ['text' => "🗑 O'chirish", 'callback_data' => "remove"]],
        ]]);
        sendMessage($cid, "👇🏻 <b>Quyidagilardan birini tanlang:</b>", $keyBot);
        mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'admins' WHERE `id` = $cid");
        exit();
    } else if ($data == "list-admin") {
        $admins = file_get_contents("admin/admins.txt");
        $keyBot = json_encode(['inline_keyboard' => [
            [['text' => "◀️ Orqaga", 'callback_data' => "admins"]],
        ]]);
        // Adminlar ro'yxatini matn sifatida yaratamiz
        $adminid = implode("\n", $admin_ids);

        // Telegram xabarini tahrirlash
        editMessageText($cid, $mid, "<b>👮 Adminlar ro'yxati:</b>\n\n$adminid", $keyBot);
    } else if ($data == "add-admin") {
        deleteMessage($cid, $mid);
        sendMessage($cid, "<b>Kerakli iD raqamni kiriting:</b>", $cancel);
        mysqli_query($connect, "UPDATE `user_id` SET `step` = 'add-admin' WHERE `id` = $cid");
    } else if ($step == "add-admin" and $cid == $iduser) {
        if (is_numeric($text) == "true") {
            if ($text != $iduser) {
                sendMessage($iduser, "✅ <b>$text endi bot admini.</b>", $panel);
                mysqli_query($connect, "UPDATE `user_id` SET `admin` = '1' WHERE `id` = $text");
                mysqli_query($connect, "UPDATE `user_id` SET `step` = '0' WHERE `id` = $cid");
                exit();
            } else {
                sendMessage($cid, "<b>Kerakli iD raqamni kiriting:</b>");
                exit();
            }
        } else {
            sendMessage($cid, "<b>Kerakli iD raqamni kiriting:</b>");
            exit();
        }
    } else if ($data == "remove") {
        deleteMessage($cid, $mid);
        sendMessage($cid, "<b>Kerakli iD raqamni kiriting:</b>", $cancel);
        mysqli_query($connect, "UPDATE `user_id` SET `step` = 'remove-admin' WHERE `id` = $cid");
        exit();
    } else if ($step == "remove-admin" and $cid == $iduser) {
        if (is_numeric($text) == "true") {
            if ($text != $iduser) {
                $files = file_get_contents("admin/admins.txt");
                $file = str_replace("{$text}", '', $files);
                file_put_contents("admin/admins.txt", $file);
                sendMessage($iduser, "✅ <b>$text endi botda admin emas.</b>", $panel);
                mysqli_query($connect, "UPDATE `user_id` SET `admin` = '0' WHERE `id` = $text");
                mysqli_query($connect, "UPDATE `user_id` SET `step` = '0' WHERE `id` = $cid");
                exit();
            } else {
                sendMessage($cid, "<b>Kerakli iD raqamni kiriting:</b>");
                exit();
            }
        } else {
            sendMessage($cid, "<b>Kerakli iD raqamni kiriting:</b>");
            exit();
        }
    }
    // end admin panel

    if ((isset($text) and $lastmsg == "start") and $text != "/start") {
        if (is_numeric($text)) {
            // Mahsulotni kod bo‘yicha qidirish
            $res = mysqli_query($connect, "SELECT * FROM `products` WHERE `code` = '$text'");
            if (!$res || mysqli_num_rows($res) == 0) {
                sendMessage($cid, "📛 $text <b>kodli mahsulot topilmadi, qayta urinib ko'ring</b>");
                exit();
            }

            $row = mysqli_fetch_assoc($res);
            $fname = $row['name'];
            $f_id = $row['img'];
            $prices = $row['price'];
            $date = $row['create_at'];
            $info = $row['info'];

            $keyBot = json_encode(['inline_keyboard' => [
                [['text' => "🔄️ Mahsulotni ulashish", 'url' => "https://t.me/share/url?url=https://t.me/$userbot?start=$text"]]
            ]]);

            sendPhoto($cid, $f_id, "<b>📄 Mahsulot nomi: $fname\n\n📍 Mahsulot haqida: <i>$info</i>\n\n💰 Narxi: $prices\n\n🕐 Yuklangan sana: $date</b>", $keyBot);
            mysqli_query($connect, "UPDATE `user_id` SET `step` = '$text' WHERE `id` = $cid");
            exit();
        } else {
            // Mahsulot nomi bo‘yicha qidirish
            $res = mysqli_query($connect, "SELECT * FROM `products`");
            $nm = null;

            while ($row = mysqli_fetch_assoc($res)) {
                $code = $row['code'];
                if (stripos($text, "$code") !== false) { // to'g'ri tekshirish
                    $nm = $code;
                    break;
                }
            }

            if (!$nm) {
                sendMessage($cid, "📛 Mahsulot topilmadi, qayta urinib ko'ring.");
                exit();
            }

            $res = mysqli_query($connect, "SELECT * FROM `products` WHERE `code` = '$nm'");
            if (!$res || mysqli_num_rows($res) == 0) {
                sendMessage($cid, "📛 Mahsulot topilmadi, qayta urinib ko'ring.");
                exit();
            }

            $row = mysqli_fetch_assoc($res);
            $fname = $row['name'];
            $f_id = $row['img'];
            $prices = $row['price'];
            $date = $row['create_at'];
            $info = $row['info'];

            $keyBot = json_encode(['inline_keyboard' => [
                [['text' => "🔄️ Mahsulotni ulashish", 'url' => "https://t.me/share/url?url=https://t.me/$userbot?start=$nm"]]
            ]]);

            sendPhoto($cid, $f_id, "<b>📄 Mahsulot nomi: $fname\n\n📍 Mahsulot haqida: <i>$info</i>\n\n💰 Narxi: $prices\n\n🕐 Yuklangan sana: $date</b>", $keyBot);
            exit();
        }
    }
}
