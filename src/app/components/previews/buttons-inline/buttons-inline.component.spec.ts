import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ButtonsInline } from './buttons-inline.component';

describe('ButtonsInline', () => {
  let component: ButtonsInline;
  let fixture: ComponentFixture<ButtonsInline>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ButtonsInline]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ButtonsInline);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
