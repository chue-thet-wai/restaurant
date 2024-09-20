<table class="table table-striped table-bordered">
    <thead>
        <tr>
            @foreach($headers as $header)
                <th scope="col">{{ $header }}</th>
            @endforeach
            <th scope="col" style="width: 250px;">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($items as $item)
            <tr>
                <!--<th scope="row">{{ $loop->iteration }}</th>-->
                @foreach($fields as $field)
                    <td>{{ $item->$field }}</td>
                @endforeach
                <td>
                    <form action="{{ route($destroyRoute, $item->id) }}" method="post">
                        @csrf
                        @method('DELETE')

                        @if ($permissions['show'])
                            <a href="{{ route($showRoute, $item->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-eye"></i> Show</a>
                        @endif
                        @if ($permissions['edit'])
                            <a href="{{ route($editRoute, $item->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Edit</a>
                        @endif                              
                        @if ($permissions['delete'])       
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Do you want to delete this item?');"><i class="bi bi-trash"></i> Delete</button>
                        @endif    
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($headers) + 1 }}">
                    <span class="text-danger">
                        <strong>No items found!</strong>
                    </span>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $items->links() }}
