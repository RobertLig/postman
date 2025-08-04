<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Carousela extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?array $dataCarousel = null,
        public ?string $input = null,
        public ?string $totalValue = null,
        public ?string $startValue = null,
        public ?string $modelName = null,
        public ?string $isLive = "", //doesn't work with boolean (false returns null). Can't assign default value for string. String 'true' or 'false' must be explicitly set on snippet tag
        //public ?string $setPropertyMethod = null,
        public ?string $prefixZero = null, //the same problem as with $isLive; Can't be used together with $textValues
        public ?array $textValues = null, //Can't be used together with $prefixZero
        
        //slots
        public mixed $inputElement,
        public mixed $progress,
    )
    {
        //dd( $this->textValues );
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
            <div class="" x-data="{ 
                rotateDegree: 20,
                currentDegree: 0,

                input: {{ $input }}, //1-100; 1
                nodeValue: 0, //0-17; inputValue

                totalValue: {{ $totalValue }}, //100
                startValue: {{ $startValue }}, //1

                nodeList: document.querySelectorAll('.picker-item'),

                //inputPlaceholder: null,

                prefixZero: {{ $prefixZero }},

                textValues: @js($textValues),

                setInput(event) {
                    if (event.deltaY < 0) { 
                        if(this.input == this.startValue) //input start from 1
                        {
                            this.input = this.totalValue;
                        }
                        else
                        {
                            this.input--;
                        }
                    } else { 
                        if(this.input == this.totalValue) //input end in 100
                        {
                            this.input = this.startValue;
                        }
                        else
                        {
                            this.input++;
                        }
                    }
                },

                prependZero(value)
                {
                    return '0' + value;
                },

                belowInput(input, node)
                {
                    if(input < this.startValue) //input start from 1; //0-(-3) max; 
                    { 
                        let belowLimit = this.startValue - input;

                        let value = this.totalValue - (belowLimit - 1);

                        if(this.prefixZero && value < 10)
                        {
                            value = this.prependZero(value);
                        }
                        else if(this.textValues)
                        {
                            value = this.textValues[value];
                        }
                                    
                        this.nodeList[node].innerHTML = value;                                      
                    }
                    else
                    {
                        if(this.prefixZero && input < 10)
                        {
                            input = this.prependZero(input);
                        }
                        else if(this.textValues)
                        {
                            input = this.textValues[input];
                        }

                        this.nodeList[node].innerHTML = input; 
                    }
                },

                aboveInput(input, node)
                {
                    if(input > this.totalValue) //input end in 100
                    {
                        let aboveLimit = input - this.totalValue;

                        let value = this.startValue + (aboveLimit - 1); //1-4 max;

                        if(this.prefixZero && value < 10)
                        {
                            value = this.prependZero(value);
                        }
                        else if(this.textValues)
                        {
                            value = this.textValues[value];
                        }

                        this.nodeList[node].innerHTML = value; 
                    }
                    else
                    {
                        if(this.prefixZero && input < 10)
                        {
                            input = this.prependZero(input);
                        }
                        else if(this.textValues)
                        {
                            input = this.textValues[input];
                        }

                        this.nodeList[node].innerHTML = input; 
                    }
                },

                setNodes() {
                    let limit = 17;
                    let total = 18;

                    for(let i = 4; i >= 1; i--) 
                    {
                        let node = this.nodeValue - i;
                        let input = this.input - i;

                        if(node < 0) 
                        {  
                            this.belowInput(input, total + node);
                        }
                        else if(node >= 0) //>= ? or > ?
                        {
                            this.belowInput(input, node);
                        }
                    }

                    if(this.prefixZero && this.input < 10)
                    {
                        this.nodeList[this.nodeValue].innerHTML = this.prependZero(this.input);
                    }
                    else if(this.textValues)
                    {
                        this.nodeList[this.nodeValue].innerHTML = this.textValues[this.input];
                    }
                    else
                    {
                        this.nodeList[this.nodeValue].innerHTML = this.input;
                    } 
                            
                    for(let i = 1; i <= 4; i++)
                    {
                        let node = this.nodeValue + i;
                        let input = this.input + i;

                        if(node > limit) 
                        {  
                            this.aboveInput(input, node - total);
                        }
                        else if(node <= limit) //<= ? or < ?
                        {
                            this.aboveInput(input, node);
                        }
                    }

                    console.log(this.input);
                },

                wheelChange(event) {
                    this.setInput(event);

                    this.setNodes();

                    /* if (event.deltaY < 0) { //-100; wheelEvent < 0; wheelEvent === -100
                        this.currentDegree -= this.rotateDegree;
                    } else { //100; wheelEvent === 100
                        this.currentDegree += this.rotateDegree;
                    }

                    $refs.carousel.style.transform = 'rotateX(' + this.currentDegree + 'deg)'; */

                    //Problem with $ in Alpine
                    /*$($refs.carousel).css({
                        '-webkit-transform': 'rotateX(' + currdeg + 'deg)',
                        '-moz-transform': 'rotateX(' + currdeg + 'deg)',
                        '-o-transform': 'rotateX(' + currdeg + 'deg)',
                        'transform': 'rotateX(' + currdeg + 'deg)'
                    });*/
                },
                    
                clickRotate(i) 
                {
                    console.log(i); //$event.target
                } }" >

                <x-dropdown>
                    <x-slot:trigger>
                        {{ $inputElement }}
                    </x-slot:trigger>

                    <div  

                        {{ $attributes->class(['h-53 perspective-distant transform-3d relative flex justify-items-center bg-base-100']) }} >

                        <div x-ref="carousel" @wheel.prevent="wheelChange"    
                            class="absolute top-21 left-1 transform-3d transition-transform duration-1000 flex items-center " > 

                            @php
                                $degrees = [0, 340, 320, 300, 280, 260, 240, 220, 200, 180, 160, 140, 120, 100, 80, 60, 40, 20];
                            @endphp

                            @for ($i = 0; $i < 18; $i++)
                                @if($i < 5)   
                                    <div @click.stop="clickRotate( {{ $i }} )" class="absolute p-1 text-base-content/70 font-semibold rounded-md hover:bg-base-200 cursor-default picker-item" style="transform: rotateX({{ $degrees[$i] }}deg) translateZ(83px)">{{ $dataCarousel[$i] }}</div> 
                                @elseif($i < 14)
                                    <div @click.stop="clickRotate( {{ $i }} )" class="absolute p-1 text-base-content/70 font-semibold rounded-md hover:bg-base-200 cursor-default picker-item" style="transform: rotateX({{ $degrees[$i] }}deg) translateZ(83px)"></div>
                                @else
                                    <div @click.stop="clickRotate( {{ $i }} )" class="absolute p-1 text-base-content/70 font-semibold rounded-md hover:bg-base-200 cursor-default picker-item" style="transform: rotateX({{ $degrees[$i] }}deg) translateZ(83px)">{{ $dataCarousel[$i - 9] }}</div> 
                                @endif
                            @endfor 
                        </div>

                        <div class="absolute top-17 h-7 w-full rounded-md bg-base-300" style="transform: translateZ(10px)"></div>

                        <x-button  @click="$wire.set( '{{ $modelName }}', textValues ? textValues[input] : input, {{ $isLive }} )" class="btn-sm self-end" label="{{ __('Set') }}" /> {{-- wire:click="{{ $setPropertyMethod }}(textValues ? textValues[input] : input)"; @click="$wire.set( '{{ $modelName }}', textValues ? textValues[input] : input, {{ $isLive }} )"; @click="$wire.setLength(input)"; inputPlaceholder = input --}}
                    </div>
 

                    {{-- wire:wheel.prevent="" --}

                    {{-- <x-menu-item title="Archive" wire:click.stop="" />
                    <x-menu-item title="Move" /> --}}
                </x-dropdown> 

                {{ $progress }}
            </div>
        blade;
    }
}
