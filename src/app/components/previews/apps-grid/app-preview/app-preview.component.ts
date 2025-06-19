import {Component, Input} from '@angular/core';
import {AppPreviewModel} from './app-preview.model';
import {ButtonsInline} from '../../buttons-inline/buttons-inline.component';

@Component({
  selector: 'app-preview',
  imports: [
    ButtonsInline
  ],
  templateUrl: './app-preview.component.html',
  styleUrl: './app-preview.component.scss'
})
export class AppPreviewComponent {
  @Input() appPreviewModel!: AppPreviewModel;
}
