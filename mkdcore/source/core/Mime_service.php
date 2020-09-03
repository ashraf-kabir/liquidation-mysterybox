<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
class Mime_service
{
    protected $_types = [
        'text/markdown' => '.md',
        'audio/aac' => '.aac',
        'application/x-abiword' => '.abw',
        'application/x-freearc' => '.arc',
        'video/x-msvideo' => '.avi',
        'application/vnd.amazon.ebook' => '.azw',
        'application/octet-stream' => '.bin',
        'image/bmp' => '.bmp',
        'application/x-bzip' => '.bz',
        'application/x-bzip2' => '.bz2',
        'application/x-csh' => '.csh',
        'text/css' => '.css',
        'text/csv' => '.csv',
        'application/msword' => '.doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => '.docx',
        'application/vnd.ms-fontobject' => '.eot',
        'application/epub+zip' => '.epub',
        'image/gif' => '.gif',
        'text/html' => '.html',
        'image/vnd.microsoft.icon' => '.ico',
        'text/calendar' => '.ics',
        'application/java-archive' => '.jar',
        'image/jpeg' => '.jpg',
        'image/jpg' => '.jpg',
        'text/javascript' => '.js',
        'application/json' => '.json',
        'application/ld+json' => '.jsonld',
        'audio/midi' => '.mid',
        'audio/x-midi' => '.midi',
        'text/javascript' => '.javascript',
        'audio/mp3' => '.mp3',
        'audio/mp4' => '.mp3',
        'audio/mpeg' => '.mp3',
        'video/mpeg' => '.mpeg',
        'application/vnd.apple.installer+xml' => '.mpkg',
        'application/vnd.oasis.opendocument.presentation' => '.odp',
        'application/vnd.oasis.opendocument.spreadsheet' => '.ods',
        'application/vnd.oasis.opendocument.text' => '.odt',
        'audio/ogg' => '.oga',
        'video/ogg' => '.ogv',
        'application/ogg' => '.ogx',
        'font/otf' => '.otf',
        'image/png' => '.png',
        'application/pdf' => '.pdf',
        'application/vnd.ms-powerpoint' => '.ppt',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => '.pptx',
        'application/x-rar-compressed' => '.rar',
        'application/rtf' => '.rtf',
        'application/x-sh' => '.sh',
        'image/svg+xml' => '.svg',
        'application/x-shockwave-flash' => '.swf',
        'application/x-tar' => '.tar',
        'image/tiff' => '.tiff',
        'video/mp2t' => '.ts',
        'font/ttf' => '.ttf',
        'text/plain' => '.txt',
        'application/vnd.visio' => '.vsd',
        'audio/wav' => '.wav',
        'audio/webm' => '.weba',
        'video/webm' => '.webm',
        'image/webp' => '.webp',
        'font/woff' => '.woff',
        'font/woff2' => '.woff2',
        'application/xhtml+xml' => '.xhtml',
        'application/vnd.ms-excel' => '.xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => '.xlsx',
        'application/xml' => '.xml',
        'text/xml' => '.xml',
        'application/vnd.mozilla.xul+xml' => '.xul',
        'application/zip' => '.zip',
        'video/3gpp' => '.3pg',
        'audio/3gpp' => '.3gp',
        'video/3gpp2' => '.3g2',
        'audio/3gpp2' => '.3g2',
        'application/x-7z-compressed' => '.7z',
        'video/mp4' => '.mp4'
    ];

    public function get_extension($type) {

        if(in_array($type, array_keys($this->_types)))
        {
            return $this->_types[$type];
        }

        return str_replace('/', '', $type);
    }
}