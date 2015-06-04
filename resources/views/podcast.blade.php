<?php echo '<?xml version="1.0"?>'; ?>
<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" version="2.0">
    <channel>
        <title>{{ $user->username }}'s Articles</title>
        <link>http://articles2podcast.herokuapp.com</link>
        <description>{{ $user->username }}'s articles, converted into speech.</description>
        <language>en-gb</language>
        <pubDate>{{ date(DATE_RFC2822) }}</pubDate>
        <lastBuildDate>{{ date(DATE_RFC2822) }}</lastBuildDate>
        <generator>https://github.com/meanbee/articles-to-podcast</generator>
        <copyright>Copyright ArticleCast</copyright>
        <itunes:image href="{{ asset('assets/images/pocket-logo-large.jpg') }}" />
        <itunes:subtitle>Pocket Audio</itunes:subtitle>
        <itunes:author>ArticleCast</itunes:author>
        <itunes:explicit>no</itunes:explicit>
        <image>
            <url>{{ asset('assets/images/pocket-logo-large.jpg') }}</url>
            <title>{{ $user->username }}'s Articles</title>
            <link>http://articles2podcast.herokuapp.com</link>
        </image>

    @foreach ($items as $item)
            <item>
                <enclosure url="https://s3-eu-west-1.amazonaws.com/articles-to-podcast/{{ md5($item->url) }}.mp3" type="audio/mpeg" length="1" />
                <title><![CDATA[{{ $item->title }}]]></title>
                <link>https://s3-eu-west-1.amazonaws.com/articles-to-podcast/{{ md5($item->url) }}.mp3</link>
                <description><![CDATA[{{ $item->excerpt }}]]></description>
                <pubDate>{{ date(DATE_RFC2822) }}</pubDate>
                <guid>https://s3-eu-west-1.amazonaws.com/articles-to-podcast/{{ md5($item->url) }}.mp3</guid>
            </item>
        @endforeach
    </channel>
</rss>
