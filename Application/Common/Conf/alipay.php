<?php
/**
 * Created by PhpStorm.
 * User: 365
 * Date: 2017/11/22
 * Time: 14:54
 */
return array(
    'ALIPAY_CONFIG'          => array(
        'partner'            => '', // partner ��֧�����̻���������Ļ�ȡ
        'seller_email'       => '', // email ��֧�����̻���������Ļ�ȡ
        'key'                => '', // key ��֧�����̻���������Ļ�ȡ
        'sign_type'          => strtoupper(trim('MD5')), // ��ѡmd5  �� RSA
        'input_charset'      => 'utf-8', // ���� (�̶�ֵ���ø�)
        'transport'          => 'http', // Э��  (�̶�ֵ���ø�)
        'cacert'             => VENDOR_PATH.'Alipay/cacert.pem',  // cacert.pem��ŵ�λ�� (�̶�ֵ���ø�)
        'notify_url'         => 'http://baijunyao.com/Api/Alipay/alipay_notify', // �첽����֧��״̬֪ͨ������
        'return_url'         => 'http://baijunyao.com/Api/Alipay/alipay_return', // ҳ����ת ͬ��֪ͨ ҳ��·�� ֧���������������,��ǰҳ���� ����ת���̻���վ��ָ��ҳ��� http ·���� (ɨ��֧��ר��)
        'show_url'           => 'http://baijunyao.com/User/Order/index', // ��Ʒչʾ��ַ,����̨ҳ����,��Ʒչʾ�ĳ����ӡ� (ɨ��֧��ר��)
        'private_key_path'   => '', //�ƶ������ɵ�˽��key�ļ�����ڷ������� ����·�� ���ΪMD5���ܷ�ʽ�������Ϊ�� (�ƶ�֧��ר��)
        'public_key_path'    => '', //�ƶ������ɵĹ���key�ļ�����ڷ������� ����·�� ���ΪMD5���ܷ�ʽ�������Ϊ�� (�ƶ�֧��ר��)
    ),
);