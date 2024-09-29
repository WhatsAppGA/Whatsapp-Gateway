@foreach ($phonebooks as $phonebook)
    <div class="row ">
        <div class="col-10">
            <a
            onclick="clickPhoneBook({{ $phonebook->id}},this)"
            href="javascript:;"
            data-phonebook-id="{{ $phonebook->id }}"
            {{-- onhover --}}

              type="button"
                class=" list-group-item d-flex align-items-center text-start single-phonebook btn-sm"><span>{{ $phonebook->name }}</span></a>
        </div>
        <div class="col-2 border-none d-flex align-items-center justify-content-center">
            <form action="{{ route('tag.delete') }}" method="POST"
                onsubmit="return confirm('{{__('do you sure want to delete this tag? ( All contacts in this tag also will delete! )')}}')">
                @method('delete')
                @csrf
                <input type="hidden" name="id" value="{{ $phonebook->id }}">
                <button type="submit" name="delete" class="btn text-danger btn-sm">
                  <i class="fa fa-trash"></i> 
                </button>
            </form>
        </div>
    </div>
@endforeach
