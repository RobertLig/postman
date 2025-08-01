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
        public mixed $input,
        public ?array $dataCarousel = null
    )
    {
        //dd($dataCarousel);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
            <div>
                <x-dropdown>
                    <x-slot:trigger>
                        {{ $input }}
                    </x-slot:trigger>

                    <div x-data="{ 
                        rotateDegree: 20,
                        currentDegree: 0,

                        input: 1, //1-100
                        inputValue: 0, //0-17

                        totalValue: 100,
                        startValue: 1,

                        nodeList: document.querySelectorAll('.picker-item'),

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

                        setNodes() {
                            let limit = 17;
                            let total = 18;

                            for(let i = 4; i >= 1; i--) 
                            {
                                let node = this.inputValue - i;
                                let input = this.input - i;

                                if(node < 0) 
                                {  
                                    if(input < this.startValue) //input start from 1; //0-(-3) max
                                    {                                       
                                        this.nodeList[total + node].innerHTML = this.totalValue + input;                                        
                                    }
                                    else
                                    {
                                        this.nodeList[total + node].innerHTML = input; 
                                    }
                                }
                                else if(node >= 0) //>= ? or > ?
                                {
                                    if(input < this.startValue) //input start from 1; 0-(-3) max
                                    {
                                        this.nodeList[node].innerHTML = this.totalValue + input; 
                                    }
                                    else
                                    {
                                        this.nodeList[node].innerHTML = input; 
                                    }
                                }
                            }

                            this.nodeList[this.inputValue].innerHTML = this.input; 
                            
                            for(let i = 1; i <= 4; i++)
                            {
                                let node = this.inputValue + i;
                                let input = this.input + i;

                                if(node > limit) 
                                {  
                                    if(input > this.totalValue) //input end in 100
                                    {
                                        this.nodeList[node - total].innerHTML = input - this.totalValue; //1-4 max; 
                                    }
                                    else
                                    {
                                        this.nodeList[node - total].innerHTML = input; 
                                    }
                                }
                                else if(node <= limit) //<= ? or < ?
                                {
                                    if(input > this.totalValue) //input end in 100
                                    {
                                        this.nodeList[node].innerHTML = input - this.totalValue; //1-4 max; 
                                    }
                                    else
                                    {
                                        this.nodeList[node].innerHTML = input; 
                                    }
                                }
                            }

                            //console.log('mama');
                        },

                        rotate(event) {
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
                        } }" 

                        {{ $attributes->class(['h-53 perspective-distant transform-3d relative flex justify-items-center bg-base-100']) }} >

                        <div x-ref="carousel" @wheel.prevent="rotate" @click.stop=""
                            class="absolute top-21 left-1 transform-3d transition-transform duration-1000 flex items-center " > 

                            @php
                                $items = [0, 340, 320, 300, 280, 260, 240, 220, 200, 180, 160, 140, 120, 100, 80, 60, 40, 20];
                            @endphp

                            @for ($i = 0; $i < 18; $i++)
                                @if($i < 5)   
                                    <div class="absolute p-1 text-base-content/70 font-semibold rounded-md hover:bg-base-200 cursor-default picker-item" style="transform: rotateX({{ $items[$i] }}deg) translateZ(83px)">{{ $dataCarousel[$i] }}</div> 
                                @elseif($i < 14)
                                    <div class="absolute p-1 text-base-content/70 font-semibold rounded-md hover:bg-base-200 cursor-default picker-item" style="transform: rotateX({{ $items[$i] }}deg) translateZ(83px)"></div>
                                @else
                                    <div class="absolute p-1 text-base-content/70 font-semibold rounded-md hover:bg-base-200 cursor-default picker-item" style="transform: rotateX({{ $items[$i] }}deg) translateZ(83px)">{{ $dataCarousel[$i - 9] }}</div> 
                                @endif
                            @endfor 
                        </div>

                        <div class="absolute top-17 h-7 w-full rounded-md bg-base-300" style="transform: translateZ(10px)"></div>

                        <x-button class="btn-sm self-end" label="{{ __('Set') }}" />
                    </div>
 
                    {{-- wire:wheel.prevent="" --}

                    {{-- <x-menu-item title="Archive" wire:click.stop="" />
                    <x-menu-item title="Move" /> --}}
                </x-dropdown> 
            </div>
        blade;
    }
}
