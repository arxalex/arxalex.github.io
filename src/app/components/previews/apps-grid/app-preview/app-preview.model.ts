import {ButtonModel} from '../../button-cta/button.model';

export interface AppPreviewModel {
  title: string;
  description: string;
  previewImage: string;
  buttons: [ButtonModel]
}
