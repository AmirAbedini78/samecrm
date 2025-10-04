<details class="tw-dw-dropdown tw-dw-dropdown-end" style="margin: 10px;">
    <summary class="tw-bg-transparent tw-text-white tw-font-medium tw-text-sm md:tw-text-base select-none">
        {{ config('constants.langs')[session()->get('user.language', config('app.locale'))]['full_name'] }}
    </summary>
    <ul
        class="tw-p-2 tw-shadow tw-dw-menu tw-dw-dropdown-content tw-z-[1] tw-w-48 md:tw-w-56 tw-bg-white tw-rounded-xl tw-mt-3">
        @foreach (config('constants.langs') as $key => $val)
            <li><a value="{{ $key }}" class="change_lang"> {{ $val['full_name'] }}</a>
            </li>
        @endforeach
    </ul>
</details>
