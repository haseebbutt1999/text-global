<table>
    <thead>
    <tr>
        <th>first_name</th>
        <th>last_name</th>
        <th>email</th>
        <th>phone</th>
        <th>currency</th>
        <th>note</th>
        <th>orders_count</th>
        <th>total_spent</th>
        <th>created_at</th>
    </tr>
    </thead>
    <tbody>
    @foreach($customer_data as $customer)
        <tr>
            <td>{{ $customer->first_name}}</td>
            <td>{{ $customer->last_name}}</td>
            <td>{{ $customer->email}}</td>
            <td>{{ json_encode($customer->phone)}}</td>
            <td>{{ $customer->currency}}</td>
            <td>{{ $customer->note}}</td>
            <td>{{ $customer->orders_count}}</td>
            <td>{{ $customer->total_spent}}</td>
            <td>{{ $customer->created_at}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
