<html>
<body>

    <table border="1">
        <thead>
            <tr>
                <th colspan="11" style="text-align: center; height:20px;">
                    <h3>INQUIRY {{ strtoupper($inquiry->fieldLang('name')) }} MESSAGE</h3>
                </th>
            </tr>
            <tr>
                <th style="width: 5px;">NO</th>
                <th style="width: 25px;">Name</th>
                <th style="width: 25px;">Email</th>
                <th style="width: 25px;">Subject</th>
                <th style="width: 35px;">Message</th>
                <th style="width: 30px;">Submit Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($inquiry->message as $key => $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{!! $item->name !!}</td>
                <td>{!! $item->email !!}</td>
                <td style="word-wrap: break-word;">{!! $item->custom_field['subject'] !!}</td>
                <td style="word-wrap: break-word;">{!! $item->custom_field['message'] !!}</td>
                <td>{!! $item->submit_time->format('d F Y (H:i)') !!}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
