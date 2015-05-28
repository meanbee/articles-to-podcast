<?php echo '<?xml version="1.0"?>'; ?>
<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" version="2.0">
    <channel>
        <title>{{ $user->username }}'s Articles</title>
        <link>{{ route('podcast', array('id' => $user->id, 'secret' => $user->secret())) }}</link>
        <description>{{ $user->username }}'s articles, converted into speech.</description>
        <language>en-gb</language>
        <pubDate>{{ date(DATE_RFC2822) }}</pubDate>
        <lastBuildDate>{{ date(DATE_RFC2822) }}</lastBuildDate>
        <generator>https://github.com/meanbee/articles-to-podcast</generator>
        <managingEditor>{{ $user->username }}</managingEditor>
        <webMaster>{{ $user->username }}</webMaster>
        <itunes:image>http://www.gravatar.com/avatar/{{ md5(strtolower('support@getpocket.com')) }}?s=500</itunes:image>
        @foreach ($items as $item)
            <item>
                <enclosure url="https://s3-eu-west-1.amazonaws.com/articles-to-podcast/{{ md5($item->url) }}.mp3" type="audio/mpeg" />
                <title>Item '{{ $item->title }}'</title>
                <link>https://s3-eu-west-1.amazonaws.com/articles-to-podcast/{{ md5($item->url) }}.mp3</link>
                <description>{{ $item->excerpt }}</description>
                <pubDate>{{ date(DATE_RFC2822) }}</pubDate>
                <guid>https://s3-eu-west-1.amazonaws.com/articles-to-podcast/{{ md5($item->url) }}.mp3</guid>
            </item>
        @endforeach
    </channel>
</rss>
