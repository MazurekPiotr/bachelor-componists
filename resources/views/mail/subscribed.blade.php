<p>
    A user subscribed to '{{ $project->title }}'. We thought you would like to know.
    <br />
    Check it out <a href="{{ env('APP_URL') . '/componists/projects/' . $project->slug }}">here</a>.
</p>
<p>
    Regards,
    <br />
    Admin @ {{ env('APP_NAME') }}.
</p>
