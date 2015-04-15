<?xml version="1.0"?>
<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" version="2.0">
    <channel>
        <title>{{ $user->name }}'s Articles</title>
        <link>{{ route('podcast', array('id' => $user->id, 'secret' => $user->secret())) }}</link>
        <description>{{ $user->name }}'s articles, converted into speech.</description>
        <language>en-gb</language>
        <pubDate>{{ date(DATE_RFC2822) }}</pubDate>
        <lastBuildDate>{{ date(DATE_RFC2822) }}</lastBuildDate>
        <generator>https://github.com/meanbee/articles-to-podcast</generator>
        <managingEditor>{{ $user->email }}</managingEditor>
        <webMaster>{{ $user->email }}</webMaster>
        <itunes:image>http://www.gravatar.com/avatar/{{ md5(strtolower($user->email)) }}?s=500</itunes:image>
        @foreach ($items as $item)
            <item>
                <enclosure url="https://s3-eu-west-1.amazonaws.com/articles-to-podcast/{{ $item->id }}.mp3" type="audio/mpeg" />
                <title>Item '{{ $item->id }}'</title>
                <link>https://s3-eu-west-1.amazonaws.com/articles-to-podcast/{{ $item->id }}.mp3</link>
                <description>{{ $item->id }}</description>
                <pubDate>{{ date(DATE_RFC2822) }}</pubDate>
                <guid>https://s3-eu-west-1.amazonaws.com/articles-to-podcast/{{ $item->id }}.mp3</guid>
            </item>
        @endforeach
    </channel>
</rss>
