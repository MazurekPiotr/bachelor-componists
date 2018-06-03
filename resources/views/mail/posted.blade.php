<p>
    A user posted on '{{ $project->title }}'. We thought you would like to know.
    <br />
    Check it out <a href="{{ env('APP_URL') . '/componists/projects/' . $project->slug . '#fragment-' . $fragment->id}}">here</a>.
</p>
<p>
    Regards,
    <br />
    Head Componist @ {{ env('APP_NAME') }}.
</p>
