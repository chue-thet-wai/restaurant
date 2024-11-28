<div class="table-responsive p-2">
    <table class="table table-striped">
        <thead>
            <tr>
                @foreach($headers as $header)
                    <th scope="col">{{ $header }}</th>
                @endforeach
                <th scope="col">Action</th>
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
                                <a href="{{ route($showRoute, $item->id) }}" class="action-icon-link"><i class="bi bi-eye" style="color:blue;"></i></a>
                            @endif
                            @if ($permissions['edit'])
                                <a href="{{ route($editRoute, $item->id) }}" class="action-icon-link"><i class="bi bi-pencil-square" style="color:gray;"></i></a>
                            @endif                              
                            @if ($permissions['delete'])       
                                <button type="submit" onclick="return confirm('Do you want to delete this item?');" class="action-icon-link"><i class="bi bi-trash" style="color:red;"></i></button>
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
</div>

{{ $items->links() }}
