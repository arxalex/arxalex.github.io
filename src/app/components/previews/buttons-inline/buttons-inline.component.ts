import {Component, Input} from '@angular/core';
import {ButtonCta} from '../button-cta/button-cta.component';
import {ButtonModel} from '../button-cta/button.model';

@Component({
  selector: 'app-buttons-inline',
  imports: [
    ButtonCta
  ],
  templateUrl: './buttons-inline.component.html',
  styleUrl: './buttons-inline.component.scss'
})
export class ButtonsInline {
  @Input() buttons!: [ButtonModel]
}
