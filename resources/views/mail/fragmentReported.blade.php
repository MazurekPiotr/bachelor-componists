<p>
    The following post has been reported on the {{ env('APP_NAME') }}: <a href="{{ env('APP_URL') . 'componists' . $project->slug . '#fragment-' . $fragment->id }}">{{ (strlen($fragment->body) > 25) ? str_limit($post->body, 24) . '&hellip;' : $fragment->body }}</a>
</p>
<p>
    Please moderate the above post by editing it or deleting it. Make sure you also check your moderator dashboard: <a href="{{ env('APP_URL') . '/moderator/dashboard/' }}">Moderator Dashboard</a> for any other topics or posts that need moderating and to clear the moderation related to this email.
</p>
<p>
    If the above links didn't work, please copy and paste the following URLs into your Browser's address bar: <em>{{ env('APP_URL') . '/componists/projects/' . $project->slug . '#fragment-' . $fragment->id }}</em>, <em>{{ env('APP_URL') . '/moderator/dashboard/' }}</em>
</p>
<p>
    Regards,
    <br />
    Admin @ {{ env('APP_NAME') }}.
</p>
