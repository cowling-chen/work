<?php
use Vendor\Overtrue\Pinyin\Pinyin;

// С�ڴ���
$pinyin = new Pinyin(); // Ĭ��
// �ڴ���
// $pinyin = new Pinyin('Overtrue\Pinyin\MemoryFileDictLoader');
// I/O��
// $pinyin = new Pinyin('Overtrue\Pinyin\GeneratorFileDictLoader');

$pinyin->convert('����ϣ��ȥ���У��ȵ����յ������');
// ["dai", "zhe", "xi", "wang", "qu", "lv", "xing", "bi", "dao", "da", "zhong", "dian", "geng", "mei", "hao"]

$pinyin->convert('����ϣ��ȥ���У��ȵ����յ������', PINYIN_UNICODE);
// ["d��i","zhe","x��","w��ng","q��","l��","x��ng","b��","d��o","d��","zh��ng","di��n","g��ng","m��i","h��o"]
$res = dir('./');
var_dump($res);exit;
$pinyin->convert('����ϣ��ȥ���У��ȵ����յ������', PINYIN_ASCII);
//["dai4","zhe","xi1","wang4","qu4","lv3","xing2","bi3","dao4","da2","zhong1","dian3","geng4","mei3","hao3"]
?>